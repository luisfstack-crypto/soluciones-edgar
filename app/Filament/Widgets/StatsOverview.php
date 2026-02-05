<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total de Pedidos', Order::count())
                ->description('Todos los pedidos')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Pedidos Completados', Order::where('status', 'completed')->count())
                ->description('Pedidos finalizados')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([2, 5, 3, 7, 5, 8, 5]),

            Stat::make('Pedidos Pendientes', Order::where('status', 'pending')->count())
                ->description('Requieren atención')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart([4, 2, 3, 1, 4, 2, 5]),
        ];
    }
}