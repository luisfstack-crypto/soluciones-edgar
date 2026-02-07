<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class AdminLatestOrders extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 6;
    protected static ?string $heading = 'Últimos Pedidos del Sistema';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->limit(15),
                Tables\Columns\TextColumn::make('service.name')
                    ->label('Servicio')
                    ->limit(15),
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
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Order $record): string => "/admin/orders/{$record->id}/edit"),
            ]);
    }
}
