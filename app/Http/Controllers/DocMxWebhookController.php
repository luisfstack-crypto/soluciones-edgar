<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Order;

class DocMxWebhookController extends Controller
{
    private const DOCMX_EVENT_STATUS_MAP = [
        'order.completed'  => 'completed',
        'order.processing' => 'processing',
        'order.failed'     => 'rejected',
        'order.canceled'   => 'rejected',
        'order.rejected'   => 'rejected',
    ];

    public function handle(Request $request)
    {
        // ── 1. Log raw incoming payload immediately (before any validation) ──
        Log::info('DocMX Webhook: raw payload received.', [
            'headers' => $request->headers->all(),
            'body'    => $request->getContent(),
        ]);
        // ── 2. Validate HMAC-SHA256 signature ────────────────────────────────
        $signature = $request->header('X-DocMX-Signature');
        $secret    = env('DOCMX_WEBHOOK_SECRET');

        if (!$signature || !$secret) {
            Log::warning('DocMX Webhook: missing signature or secret configuration.');
            return response()->json(['error' => 'Missing signature or secret'], 400);
        }

        $expectedSignature = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('DocMX Webhook: invalid HMAC signature.', [
                'received' => $signature,
                'expected' => $expectedSignature,
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // ── 3. Parse payload ─────────────────────────────────────────────────
        $payload         = $request->json()->all();
        $event           = $payload['event']             ?? 'unknown';
        $externalOrderId = $payload['external_order_id'] ?? null;

        Log::info('DocMX Webhook: payload parsed.', [
            'event'             => $event,
            'external_order_id' => $externalOrderId,
            'payload'           => $payload,
        ]);

        if (!$externalOrderId) {
            Log::error('DocMX Webhook: missing external_order_id in payload.');
            return response()->json(['error' => 'Missing external_order_id'], 400);
        }

        // ── 4. Locate the local order by ID ──────────────────────────────────
        // DocMX returns the value we passed as external_order_id (= our Order PK).
        $order = Order::find($externalOrderId);

        Log::info('DocMX Webhook: order lookup result.', [
            'external_order_id' => $externalOrderId,
            'order_found'       => $order ? $order->id : null,
        ]);

        if (!$order) {
            Log::error("DocMX Webhook: local order not found for external_order_id: {$externalOrderId}");
            return response()->json(['error' => 'Order not found'], 404);
        }

        // ── 5. Map DocMX event to a valid local status ────────────────────────
        $mappedStatus = self::DOCMX_EVENT_STATUS_MAP[$event] ?? null;

        if ($mappedStatus === null) {
            Log::info("DocMX Webhook: unhandled event '{$event}' for order {$order->id}. No status change.");
            return response()->json(['received' => true], 200);
        }

        // ── 6. Handle each event ──────────────────────────────────────────────
        if ($event === 'order.completed') {
            $documentUrl = $payload['document_url'] ?? null;

            if ($documentUrl) {
                try {
                    $pdfContent = Http::withHeaders(['ngrok-skip-browser-warning' => 'true'])
                        ->get($documentUrl)
                        ->body();

                    $fileName = 'order-results/result_' . $order->id . '_' . time() . '.pdf';

                    Storage::disk('s3')->put($fileName, $pdfContent);

                    $order->update([
                        'status'           => 'completed',
                        'result_file_path' => $fileName,
                    ]);

                    Log::info("DocMX Webhook: order {$order->id} completed — PDF saved to R2.", [
                        'file' => $fileName,
                    ]);
                } catch (\Throwable $e) {
                    Log::error("DocMX Webhook: error downloading/saving PDF for order {$order->id} — " . $e->getMessage());
                    // Still mark as completed even if file save fails, to avoid infinite retries.
                    $order->update(['status' => 'completed']);
                    return response()->json(['error' => 'Error processing file download'], 500);
                }
            } else {
                // Completed with no document URL (e.g. non-PDF service)
                $order->update(['status' => 'completed']);
                Log::info("DocMX Webhook: order {$order->id} marked completed (no document_url provided).");
            }
        } elseif ($event === 'order.processing') {
            $order->update(['status' => 'processing']);
            Log::info("DocMX Webhook: order {$order->id} is now processing.");
        } else {
            // order.failed / order.canceled / order.rejected → 'rejected'
            $errorMessage = $payload['error_message'] ?? $payload['message'] ?? 'Unknown error';

            $order->update([
                'status'       => $mappedStatus,
                'admin_notes'  => "DocMX {$event}: {$errorMessage}",
            ]);

            Log::warning("DocMX Webhook: order {$order->id} → '{$mappedStatus}'. Event: {$event}. Reason: {$errorMessage}");
        }

        return response()->json(['received' => true], 200);
    }
}