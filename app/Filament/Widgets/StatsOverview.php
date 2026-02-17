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
            $pendingDeposits = \App\Models\DepositRequest::where('status', 'pending');
            $pendingAmount = $pendingDeposits->sum('amount');
            $pendingCount = $pendingDeposits->count();

            return [
                Stat::make('Total de Pedidos', Order::count())
                    ->description('Volumen total de solicitudes')
                    ->descriptionIcon('heroicon-m-shopping-bag')
                    ->chart([7, 2, 10, 3, 15, 4, 18])
                    ->color('primary')
                    ->extraAttributes([
                        'class' => 'cursor-pointer hover:shadow-lg transition-transform hover:-translate-y-1',
                    ]),

                Stat::make('Ingresos por Aprobar', '$' . number_format($pendingAmount, 2))
                    ->description($pendingCount . ' recargas en espera de validación')
                    ->descriptionIcon('heroicon-m-arrow-path')
                    ->chart([15, 12, 18, 10, 15, 20, 25])
                    ->color('warning')
                    ->extraAttributes([
                        'class' => 'cursor-pointer hover:shadow-lg transition-transform hover:-translate-y-1',
                    ]),

                Stat::make('Pedidos Pendientes', Order::where('status', 'pending')->count())
                    ->description('Trámites que requieren atención inmediata')
                    ->descriptionIcon('heroicon-m-exclamation-circle')
                    ->chart([1, 5, 2, 8, 4, 10, 6])
                    ->color('danger')
                    ->extraAttributes([
                        'class' => 'cursor-pointer hover:shadow-lg transition-transform hover:-translate-y-1 animate-pulse',
                    ]),

                Stat::make('Clientes Activos', \App\Models\User::where('is_admin', false)->count())
                    ->description('Usuarios registrados en la plataforma')
                    ->descriptionIcon('heroicon-m-users')
                    ->chart([3, 7, 5, 12, 8, 15, 10])
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'cursor-pointer hover:shadow-lg transition-transform hover:-translate-y-1',
                    ]),
            ];
        }

        return [
            Stat::make('Saldo Actual', '$' . number_format($user->balance, 2))
                ->description('Saldo disponible para servicios')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->extraAttributes([
                    'class' => 'transform transition-all hover:scale-105',
                ]),

            Stat::make('Mis Pedidos', $user->orders()->count())
                ->description('Historial total')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'transform transition-all hover:scale-105',
                ]),

            Stat::make('En Proceso', $user->orders()->whereIn('status', ['pending', 'processing'])->count())
                ->description('Pedidos activos')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'transform transition-all hover:scale-105',
                ]),
        ];
    }
}