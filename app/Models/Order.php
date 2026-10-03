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
        'batch_id',
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
        return $this->result_file_path
            ? \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'orders.download',
                now()->addDays(7),
                ['order' => $this->id],
            )
            : null;
    }

    public function getElapsedTimeFormattedAttribute(): string
    {
        if (!$this->created_at) {
            return '0 min';
        }

        $diffInMinutes = (int) $this->created_at->diffInMinutes(now());
        $hours = intdiv($diffInMinutes, 60);
        $minutes = $diffInMinutes % 60;

        if ($hours > 0) {
            return "{$hours} h {$minutes} min";
        }

        return "{$minutes} min";
    }

    public function getIsDelayedAttribute(): bool
    {
        if (!$this->created_at || !$this->service || empty($this->service->processing_time)) {
            return false;
        }

        preg_match_all('/\d+/', (string) $this->service->processing_time, $matches);

        if (empty($matches[0])) {
            return false;
        }

        $maxMinutes = max(array_map('intval', $matches[0]));

        return $this->created_at->diffInMinutes(now()) > $maxMinutes;
    }
}
