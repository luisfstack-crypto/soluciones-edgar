<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Pedidos por Día (Última Semana)';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        $labels = [];
        $pendingData = [];
        $processingData = [];
        $completedData = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->isoFormat('D MMM');
            $dateStr = $date->format('Y-m-d');
            
            $pendingData[] = Order::where('status', 'pending')->whereDate('created_at', $dateStr)->count();
            $processingData[] = Order::where('status', 'processing')->whereDate('created_at', $dateStr)->count();
            $completedData[] = Order::where('status', 'completed')->whereDate('created_at', $dateStr)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Completados',
                    'data' => $completedData,
                    'borderColor' => '#10b981', // green-500
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => 'start',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'En Proceso',
                    'data' => $processingData,
                    'borderColor' => '#0ea5e9', // sky-500
                    'backgroundColor' => 'rgba(14, 165, 233, 0.1)',
                    'fill' => 'start',
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Pendientes',
                    'data' => $pendingData,
                    'borderColor' => '#f59e0b', // amber-500
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'fill' => 'start',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
