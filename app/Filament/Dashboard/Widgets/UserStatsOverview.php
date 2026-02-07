<?php

namespace App\Filament\Dashboard\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        return [
            Stat::make('Mi Saldo', '$' . number_format($user->balance, 2))
                ->description('Disponible para usar')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Pedidos en Proceso', $user->orders()->whereIn('status', ['pending', 'processing'])->count())
                ->description('Trabajando en ello')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Total Pedidos', $user->orders()->count())
                ->description('Historial completo')
                ->color('gray'),
        ];
    }
}
