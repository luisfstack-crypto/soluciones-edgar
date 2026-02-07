<?php

namespace App\Filament\Resources\DepositRequestResource\Pages;

use App\Filament\Resources\DepositRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDepositRequest extends EditRecord
{
    protected static string $resource = DepositRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
