<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Service;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::find($data['user_id']);
        $service = Service::find($data['service_id']);

        if (!$user || !$service) {
             return $data; 
        }

        if ($user->balance < $service->price && !auth()->user()->is_admin) {
            Notification::make()
                ->title('Saldo Insuficiente')
                ->body("El usuario tiene \${$user->balance} pero el servicio cuesta \${$service->price}.")
                ->danger()
                ->send();
            
            $this->halt();
        }

        
        return $data;
    }
}
