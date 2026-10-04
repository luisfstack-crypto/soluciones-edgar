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
        'completed_at' => 'datetime',
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

        $endTime = $this->status === 'completed'
            ? ($this->completed_at ?? $this->updated_at ?? now())
            : now();

        $diffInSeconds = (int) $this->created_at->diffInSeconds($endTime);

        if ($diffInSeconds < 60) {
            return 'Menos de 1 min';
        }

        $diffInMinutes = intdiv($diffInSeconds, 60);

        if ($diffInMinutes < 60) {
            return "{$diffInMinutes} min";
        }

        $days = intdiv($diffInMinutes, 1440);
        $hours = intdiv($diffInMinutes % 1440, 60);
        $minutes = $diffInMinutes % 60;

        if ($days > 0) {
            return $hours > 0 ? "{$days} d {$hours} h" : "{$days} d";
        }

        return $minutes > 0 ? "{$hours} h {$minutes} min" : "{$hours} h";
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
