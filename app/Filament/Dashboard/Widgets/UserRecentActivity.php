<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UserRecentActivity extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 6;
    protected static ?string $heading = 'Mis Actividades Recientes';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->where('user_id', auth()->id())->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio')
                    ->limit(20),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'info',
                        'completed' => 'success',
                        'rejected' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'processing' => 'En Proceso',
                        'completed' => 'Completado',
                        'rejected' => 'Rechazado',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Hace')
                    ->since()
                    ->dateTimeTooltip(),
            ]);
    }
}
