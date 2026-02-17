<?php

namespace App\Observers;

use App\Models\DepositRequest;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;

class DepositRequestObserver
{
    public function created(DepositRequest $depositRequest): void
    {
        // Notify Admins
        Notification::make()
            ->title('Nueva Solicitud de Saldo')
            ->body("Usuario: {$depositRequest->user->name}. Monto: \${$depositRequest->amount}")
            ->info()
            ->actions([
                Action::make('view')
                    ->label('Ver')
                    ->url('/admin/deposit-requests/' . $depositRequest->id . '/edit')
            ])
            ->sendToDatabase(User::where('is_admin', true)->get());
    }

    public function updated(DepositRequest $depositRequest): void
    {
        if ($depositRequest->wasChanged('status') && $depositRequest->status === 'approved') {
            // Logic to add balance should ideally be here to ensure it happens regardless of WHERE it was approved
            // But we must be careful not to duplicate if the controller/action already did it.
            // Best pattern: The Action only sets status. The Observer does the logic.
            
            // Check if balance was already added? Hard to track. 
            // Instead, we will rely on the fact that we will REFACTOR the Resource Action to ONLY update status.
            
            if (method_exists($depositRequest->user, 'addBalance')) {
                $depositRequest->user->addBalance(
                    $depositRequest->amount, 
                    'deposit', 
                    "Recarga Aprobada (Ref: {$depositRequest->tracking_key})", 
                    $depositRequest
                );
            } else {
                $depositRequest->user->balance += $depositRequest->amount;
                $depositRequest->user->save();
            }

            Notification::make()
                ->title('¡Depósito Aprobado!')
                ->body("Se han abonado \${$depositRequest->amount} a tu cuenta correctamente.")
                ->success()
                ->actions([
                    Action::make('view')
                        ->label('Ver Mi Billetera')
                        ->url('/app/wallet')
                ])
                ->sendToDatabase($depositRequest->user);
        }

        if ($depositRequest->wasChanged('status') && $depositRequest->status === 'rejected') {
            Notification::make()
                ->title('Depósito Rechazado')
                ->body("Tu solicitud de \${$depositRequest->amount} fue rechazada. Motivo: " . ($depositRequest->admin_notes ?? 'No especificado.'))
                ->danger()
                ->sendToDatabase($depositRequest->user);
        }
    }
}
