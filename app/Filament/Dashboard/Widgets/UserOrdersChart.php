<?php

namespace App\Filament\Dashboard\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class UserOrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Mis Pedidos';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 6;

    protected function getData(): array
    {
        $user = auth()->user();
        $labels = [];
        $counts = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->isoFormat('D MMM');
            $dateStr = $date->format('Y-m-d');
            
            $counts[] = $user->orders()->whereDate('created_at', $dateStr)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Mis Pedidos',
                    'data' => $counts,
                    'borderColor' => '#0ea5e9',
                    'backgroundColor' => 'rgba(14, 165, 233, 0.1)',
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
