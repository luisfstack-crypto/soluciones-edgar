<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "creating" event.
     */
    public function creating(Order $order): void
    {
        // Snapshot price
        if ($order->service) {
            $order->price_at_purchase = $order->service->price;
        }
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Deduct Balance
        // We do this in created so we have the Order ID for reference
        if ($order->price_at_purchase > 0) {
            try {
                $order->user->subtractBalance(
                    $order->price_at_purchase, 
                    "Pago de servicio: {$order->service->name} (Pedido #{$order->id})", 
                    $order
                );
            } catch (\Exception $e) {
                // This exception will rollback the transaction preventing order creation
                // Filament should catch this and show the error message
                throw $e;
            }
        }

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id() ?? $order->user_id,
            'event' => 'order_created',
            'subject_type' => Order::class,
            'subject_id' => $order->id,
            'description' => "Pedido #{$order->id} creado. Costo: \${$order->price_at_purchase}",
            'properties' => $order->toArray(),
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Refund Logic
        if ($order->isDirty('status') && $order->status === 'rejected') {
            // Ensure we don't refund if it was somehow already refunded (unlikely if status flow is strict, but good to be safe)
            // Ideally we check transactions using reference, but simplistic check:
            // Only refund if moving from a paid state.
            
            // Check if price > 0
            $refundAmount = $order->price_at_purchase ?? $order->service->price;
            
            if ($refundAmount > 0) {
                 $order->user->credit(
                    $refundAmount, 
                    "Reembolso por pedido #{$order->id} (Servicio no disponible/Rechazado)",
                    $order
                );
                
                \Filament\Notifications\Notification::make()
                    ->title('Reembolso Procesado')
                    ->body("Se han devuelto \${$refundAmount} al usuario.")
                    ->success()
                    ->sendToDatabase(\App\Models\User::where('is_admin', true)->get());
            }
        }

        // Email Logic
        // Email Logic
        if ($order->isDirty('status') && $order->status === 'completed') {
            try {
                \Illuminate\Support\Facades\Mail::to($order->user->email)
                    ->send(new \App\Mail\OrderCompleted($order));
                
                \App\Models\ActivityLog::create([
                    'user_id' => $order->user_id, // The recipient
                    'event' => 'email_sent',
                    'subject_type' => Order::class,
                    'subject_id' => $order->id,
                    'description' => "Email de resultado enviado a {$order->user->email}",
                    'ip_address' => request()->ip(),
                ]);
            } catch (\Exception $e) {
                \Log::error("Error enviando correo de pedido completado: " . $e->getMessage());
                // Consider logging the failure too
                \App\Models\ActivityLog::create([
                     'user_id' => auth()->id(),
                     'event' => 'email_failed',
                     'subject_type' => Order::class,
                     'subject_id' => $order->id,
                     'description' => "Fallo el envío de email: " . $e->getMessage(),
                     'ip_address' => request()->ip(),
                ]);
            }
        }
        
        // Log Status Change
        if ($order->isDirty('status')) {
             \App\Models\ActivityLog::create([
                'user_id' => auth()->id() ?? $order->user_id,
                'event' => 'status_updated',
                'subject_type' => Order::class,
                'subject_id' => $order->id,
                'description' => "Estado cambiado de '{$order->getOriginal('status')}' a '{$order->status}'",
                'properties' => ['old' => $order->getOriginal('status'), 'new' => $order->status],
                'ip_address' => request()->ip(),
            ]);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
