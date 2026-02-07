<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminQuickActions extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        return [
            Stat::make('Catálogo', 'Gestionar Servicios')
                ->description('Configurar precios y formularios')
                ->descriptionIcon('heroicon-m-adjustments-horizontal')
                ->color('primary')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-gray-50 dark:hover:bg-white/5 transition-all shadow-sm border-l-4 border-primary-500',
                    'onclick' => "window.location.href='/admin/services'",
                ]),

            Stat::make('Nuevo Servicio', 'Crear Ahora')
                ->description('Añadir un nuevo trámite al sistema')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:bg-gray-50 dark:hover:bg-white/5 transition-all shadow-sm border-l-4 border-success-500',
                    'onclick' => "window.location.href='/admin/services/create'",
                ]),
        ];
    }
}
