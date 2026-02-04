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
            Stat::make('Su saldo actual', '$' . (auth()->user()->balance ?? 0) . ' MXN')
                ->description('Saldo disponible en cuenta')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Los pedidos se procesan con éxito', Order::where('status', 'completed')->count())
                ->description('Total finalizados')
                ->color('success'),

            Stat::make('Pedidos en proceso', Order::where('status', 'pending')->count())
                ->description('Esperando revisión')
                ->color('warning'),
        ];
    }
}