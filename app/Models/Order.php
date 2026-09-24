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

                Log::info("Order {$order->id} sent to DocMX.", ['docmx_response' => $response]);

            } catch (\Exception $e) {
                Log::error("Failed to send order {$order->id} to DocMX: " . $e->getMessage());
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
