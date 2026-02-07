<?php

namespace App\Filament\Dashboard\Resources\DepositRequestResource\Pages;

use App\Filament\Dashboard\Resources\DepositRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepositRequests extends ListRecords
{
    protected static string $resource = DepositRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
