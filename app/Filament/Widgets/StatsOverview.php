<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Saldo Actual', '$' . number_format(auth()->user()->balance ?? 0, 2) . ' MXN')
                ->description('Saldo disponible')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pedidos con Éxito', Order::where('status', 'completed')->count())
                ->description('Total finalizados')
                ->color('success'),

            Stat::make('Pedidos en Proceso', Order::whereIn('status', ['pending', 'processing'])->count())
                ->description('Activos')
                ->color('warning'),
        ];
    }
}