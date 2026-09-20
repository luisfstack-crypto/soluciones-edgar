<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Order; 

class DocMxWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $signature = $request->header('X-DocMX-Signature');
        $secret = env('DOCMX_WEBHOOK_SECRET');

        if (!$signature || !$secret) {
            return response()->json(['error' => 'Missing signature or secret'], 400);
        }

        // Verify HMAC-SHA256 signature using the raw body payload
        $expectedSignature = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('DocMX Webhook: Invalid security signature detected.');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $payload = $request->json()->all();
        $event = $payload['event'] ?? 'unknown';
        $externalOrderId = $payload['external_order_id'] ?? null;

        if (!$externalOrderId) {
            return response()->json(['error' => 'Missing external_order_id'], 400);
        }

        // Find the local order (assuming external_order_id corresponds to the local Order ID)
        $order = Order::find($externalOrderId);

        if (!$order) {
            Log::error("DocMX Webhook: Local order not found for external_order_id: {$externalOrderId}");
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($event === 'order.completed') {
            $documentUrl = $payload['document_url'] ?? null;

            if ($documentUrl) {
                try {
                    // Download the PDF from DocMX signed URL
                    $pdfContent = Http::withHeaders(['ngrok-skip-browser-warning' => 'true'])
                        ->get($documentUrl)->body();

                    // Generate a unique filename for Cloudflare R2 storage
                    $fileName = 'order-results/result_' . $order->id . '_' . time() . '.pdf';

                    // Save directly to the s3 disk
                    Storage::disk('s3')->put($fileName, $pdfContent);

                    // Update local database record
                    $order->update([
                        'result_file_path' => $fileName,
                        'status' => 'completed', 
                    ]);

                    Log::info("DocMX Webhook: Order {$order->id} completed and PDF saved to R2.");
                } catch (\Exception $e) {
                    Log::error("DocMX Webhook: Error downloading or saving PDF - " . $e->getMessage());
                    return response()->json(['error' => 'Error processing file download'], 500);
                }
            }
        } elseif ($event === 'order.failed') {
            $errorMessage = $payload['error_message'] ?? 'Unknown error';
            
            // Update local database record to failed
            $order->update([
                'status' => 'failed',
                // 'notes' => $errorMessage // Optional: if you have a field to store error details
            ]);

            Log::warning("DocMX Webhook: Order {$order->id} failed. Reason: {$errorMessage}");
        }

        // DocMX requires a 2xx response to confirm receipt and stop retries
        return response()->json(['received' => true], 200);
    }
}