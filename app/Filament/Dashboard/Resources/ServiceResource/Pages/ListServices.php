<?php

namespace App\Filament\Dashboard\Resources\ServiceResource\Pages;

use App\Filament\Dashboard\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('create_service')
                ->label('Crear Nuevo Servicio')
                ->icon('heroicon-m-plus')
                ->color('primary')
                ->url('/admin/services/create')
                ->visible(fn () => auth()->user()->is_admin),
        ];
    }
}
