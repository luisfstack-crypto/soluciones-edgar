<?php

namespace App\Filament\Dashboard\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();

        $isAdmin = $user->is_admin;

        return [
            Stat::make('Mi Saldo', $isAdmin ? 'Ilimitado' : '$' . number_format($user->balance, 2))
                ->description($isAdmin ? 'Acceso Total Administrador' : 'Fondos disponibles para contratar')
                ->descriptionIcon($isAdmin ? 'heroicon-m-shield-check' : 'heroicon-m-banknotes')
                ->chart($isAdmin ? [10, 10, 10, 10, 10, 10, 10] : [10, 20, 15, 30, 25, 40, $user->balance])
                ->color($isAdmin ? 'primary' : 'success'),

            Stat::make('Trámites en Curso', 
                $user->orders()->whereIn('status', ['pending', 'processing'])->count() + 
                $user->depositRequests()->where('status', 'pending')->count()
            )
                ->description('Servicios y recargas en proceso')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([2, 4, 3, 5, 4, 6, 2])
                ->color('warning'),

            Stat::make('Total Pedidos', $user->orders()->count())
                ->description('Historial acumulado de servicios')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([5, 10, 8, 12, 7, 15, 10])
                ->color('primary'),

            Stat::make('Inversión Total', '$' . number_format($user->transactions()->where('type', 'purchase')->sum(\DB::raw('ABS(amount)')), 2))
                ->description('Monto total invertido en servicios')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->chart([50, 150, 200, 450, 300, 600, 800])
                ->color('info'),
        ];
    }
}
