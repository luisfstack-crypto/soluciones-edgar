<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Services\DocMxService;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'input_data',
        'status',
        'result_file_path',
        'admin_notes',
        'price_at_purchase',
        'service_cost_snapshot',
        'service_price_snapshot',
    ];

    protected $casts = [
        'input_data' => 'array',
    ];

    protected static function booted(): void
    {
        static::created(function (Order $order) {
            $formData = is_array($order->input_data) ? $order->input_data : [];

            try {
                $docMx = new DocMxService();
                $serviceCode = $order->service ? $order->service->code : $order->service_id;

                $response = $docMx->createOrder(
                    $serviceCode, 
                    $formData,
                    (string) $order->id
                );

                if ($response->successful()) {
                    Log::info("Order {$order->id} sent to DocMX.", ['docmx_response' => $response->json()]);
                } else {
                    $errorData = $response->json() ?? [];
                    $errorMessage = $errorData['message'] ?? $response->body();
                    $statusCode = $response->status();

                    Log::error("DocMX rejected order {$order->id} [HTTP {$statusCode}]: {$errorMessage}", [
                        'payload' => [
                            'service_id' => $serviceCode,
                            'form_data' => $formData,
                            'external_order_id' => (string) $order->id,
                        ],
                        'response' => $errorData,
                    ]);

                    $order->update([
                        'status' => 'rejected',
                        'admin_notes' => "Error DocMX ({$statusCode}): {$errorMessage}",
                    ]);
                }

            } catch (\Throwable $e) {
                Log::error("Failed to send order {$order->id} to DocMX: " . $e->getMessage());

                $order->update([
                    'admin_notes' => "Fallo de conexión DocMX: " . $e->getMessage(),
                ]);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->result_file_path ? route('orders.download', ['order' => $this->id]) : null;
    }
}
