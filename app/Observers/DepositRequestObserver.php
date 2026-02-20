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

        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            \Illuminate\Support\Facades\Mail::to($admin->email)->send(new \App\Mail\AdminDepositCreated($depositRequest));
        }
    }

    public function updated(DepositRequest $depositRequest): void
    {
        if ($depositRequest->wasChanged('status') && $depositRequest->status === 'approved') {
            
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
                        ->url('/app/movements')
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
