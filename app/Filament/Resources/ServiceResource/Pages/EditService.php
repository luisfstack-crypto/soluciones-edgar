<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use App\Models\Service;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('toggleMaintenance')
                ->label(fn (): string => $this->record->is_maintenance ? 'Reactivar servicio' : 'Poner en mantenimiento')
                ->color(fn (): string => $this->record->is_maintenance ? 'success' : 'warning')
                ->icon(fn (): string => $this->record->is_maintenance ? 'heroicon-o-check-circle' : 'heroicon-o-wrench-screwdriver')
                ->requiresConfirmation()
                ->modalHeading(fn (): string => $this->record->is_maintenance ? 'Reactivar servicio' : 'Poner en mantenimiento')
                ->modalDescription(fn (): string => $this->record->is_maintenance
                    ? 'Los clientes podrán solicitar este servicio si está dentro de su horario.'
                    : 'Los clientes no podrán solicitar este servicio mientras esté en mantenimiento.')
                ->form(fn (): array => $this->record->is_maintenance ? [] : [
                    TextInput::make('maintenance_message')
                        ->label('Mensaje de mantenimiento (opcional)')
                        ->placeholder('Ej. Volvemos mañana a las 9:00')
                        ->maxLength(255),
                ])
                ->action(function (array $data): void {
                    /** @var Service $service */
                    $service = $this->record;
                    $wasInMaintenance = $service->is_maintenance;
                    $service->is_maintenance = ! $wasInMaintenance;
                    $service->maintenance_message = $service->is_maintenance
                        ? ($data['maintenance_message'] ?? $service->maintenance_message)
                        : null;
                    $service->save();

                    Notification::make()
                        ->title($service->is_maintenance ? 'Servicio en mantenimiento' : 'Servicio reactivado')
                        ->success()
                        ->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
