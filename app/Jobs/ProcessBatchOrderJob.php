<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;

class ProcessBatchOrderJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maximum number of times the job may be attempted before failing.
     * Keep low to avoid flooding DocMX with retries.
     */
    public int $tries = 2;

    /**
     * Timeout in seconds before the job is considered failed.
     */
    public int $timeout = 60;

    public function __construct(
        public readonly User    $user,
        public readonly Service $service,
        public readonly array   $inputData,
    ) {}

    public function handle(): void
    {
        // If the whole batch was cancelled before this job ran, skip it.
        if ($this->batch()?->cancelled()) {
            Log::info('ProcessBatchOrderJob: batch cancelled, skipping.', [
                'batch_id'   => $this->batch()?->id,
                'service_id' => $this->service->id,
                'user_id'    => $this->user->id,
            ]);
            return;
        }

        $this->service->refresh();

        try {
            DB::transaction(function () {
                $service = Service::query()->lockForUpdate()->findOrFail($this->service->id);

                if (! $service->isAvailable()) {
                    $this->user->credit(
                        $service->price,
                        "Reembolso por servicio no disponible (Pedido por lote)",
                    );

                    $reason = $service->unavailableReason();
                    Log::warning('ProcessBatchOrderJob: unavailable service, order skipped and refunded.', [
                        'batch_id' => $this->batch()?->id,
                        'service_id' => $service->id,
                        'user_id' => $this->user->id,
                        'reason' => $reason,
                    ]);

                    Notification::make()
                        ->title('Servicio no disponible')
                        ->body($reason)
                        ->warning()
                        ->sendToDatabase($this->user);

                    return;
                }

                // Guard: block identical in-flight orders (same user / service / data)
                $alreadyExists = false;

                try {
                    $alreadyExists = Order::where('user_id', $this->user->id)
                        ->where('service_id', $this->service->id)
                        ->whereIn('status', ['pending', 'processing'])
                        ->whereJsonContains('input_data', $this->inputData)
                        ->exists();
                } catch (\Throwable) {
                    // Driver does not support whereJsonContains — fall back to PHP comparison.
                    $alreadyExists = Order::where('user_id', $this->user->id)
                        ->where('service_id', $this->service->id)
                        ->whereIn('status', ['pending', 'processing'])
                        ->get()
                        ->contains(fn (Order $o) => $o->input_data === $this->inputData);
                }

                if ($alreadyExists) {
                    Log::warning('ProcessBatchOrderJob: duplicate order skipped.', [
                        'batch_id'    => $this->batch()?->id,
                        'service_code' => $this->service->code,
                        'user_id'     => $this->user->id,
                        'input_data'  => $this->inputData,
                    ]);
                    // Don't fail the batch — just skip this item silently.
                    return;
                }

                // Create the order.
                // The Order::booted() created hook will automatically dispatch it to DocMX.
                $order = Order::create([
                    'user_id'                  => $this->user->id,
                    'service_id'               => $service->id,
                    'input_data'               => $this->inputData,
                    'status'                   => 'pending',
                    'price_at_purchase'        => $service->price,
                    'service_cost_snapshot'    => $service->cost    ?? null,
                    'service_price_snapshot'   => $service->price   ?? null,
                    'batch_id'                 => $this->batch()?->id,
                ]);

                Log::info('ProcessBatchOrderJob: order created.', [
                    'order_id' => $order->id,
                    'batch_id' => $this->batch()?->id,
                    'user_id'  => $this->user->id,
                ]);

                // Notify admins of the new order (same pattern as BuyService::submit).
                $admins = User::where('is_admin', true)->get();
                foreach ($admins as $admin) {
                    Notification::make()
                        ->title('Nuevo Pedido (Lote)')
                        ->body("El usuario {$this->user->name} solicitó {$this->service->name} vía lote.")
                        ->info()
                        ->actions([
                            \Filament\Notifications\Actions\Action::make('view')
                                ->label('Ver Pedido')
                                ->url("/admin/orders/{$order->id}/edit"),
                        ])
                        ->sendToDatabase($admin);
                }
            });
        } catch (\Throwable $e) {
            // Log the failure so it is visible in Laravel logs and the failed_jobs table.
            Log::error('ProcessBatchOrderJob: failed to create order.', [
                'batch_id'    => $this->batch()?->id,
                'service_code' => $this->service->code,
                'user_id'     => $this->user->id,
                'input_data'  => $this->inputData,
                'error'       => $e->getMessage(),
            ]);

            // Re-throw so Laravel marks this job as failed and the batch tracks it.
            throw $e;
        }
    }

    /**
     * Handle a job failure after all retry attempts are exhausted.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('ProcessBatchOrderJob: permanently failed after retries.', [
            'batch_id'    => $this->batch()?->id,
            'service_code' => $this->service->code,
            'user_id'     => $this->user->id,
            'input_data'  => $this->inputData,
            'error'       => $exception->getMessage(),
        ]);
    }
}
