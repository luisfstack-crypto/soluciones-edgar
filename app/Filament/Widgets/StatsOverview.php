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
        $user = auth()->user();

        if ($user->is_admin) {
             return [
                Stat::make('Total de Pedidos', Order::count())
                    ->description('Todos los pedidos')
                    ->descriptionIcon('heroicon-m-shopping-bag')
                    ->color('primary'),

                Stat::make('Ingresos Totales', '$' . number_format(Order::where('status', 'completed')->sum('price_at_purchase') ?? 0, 2)) // Assuming we track this now
                    ->description('Estimado en pedidos completados')
                    ->color('success'),

                Stat::make('Pedidos Pendientes', Order::where('status', 'pending')->count())
                    ->description('Requieren atención')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('warning'),
            ];
        }

        return [
            Stat::make('Saldo Actual', '$' . number_format($user->balance, 2))
                ->description('Saldo disponible para servicios')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Mis Pedidos', $user->orders()->count())
                ->description('Historial total')
                ->color('primary'),

            Stat::make('En Proceso', $user->orders()->whereIn('status', ['pending', 'processing'])->count())
                ->description('Pedidos activos')
                ->color('warning'),
        ];
    }
}