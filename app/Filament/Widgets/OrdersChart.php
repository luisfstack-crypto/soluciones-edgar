<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Pedidos por Día (Última Semana)';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Calculate simple daily counts for the last 7 days using PHP to be database agnostic and safe
        $data = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->isoFormat('D MMM'); // e.g. "5 Feb"
            
            // This is a bit N+1 but safe for small dashboard charts vs complex SQL aggregations that might crash with driver mismatches
            $count = Order::whereDate('created_at', $date->format('Y-m-d'))->count();
            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pedidos',
                    'data' => $data,
                    'borderColor' => '#4f46e5', // Indigo-600
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                    'pointBackgroundColor' => '#4f46e5',
                    'pointBorderColor' => '#ffffff',
                    'pointHoverBackgroundColor' => '#ffffff',
                    'pointHoverBorderColor' => '#4f46e5',
                    'fill' => true,
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
