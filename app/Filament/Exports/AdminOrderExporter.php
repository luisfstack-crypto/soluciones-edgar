<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AdminOrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID Pedido'),
            ExportColumn::make('user.name')
                ->label('Usuario'),
            ExportColumn::make('service.name')
                ->label('Servicio'),
            ExportColumn::make('status')
                ->label('Estado'),
            ExportColumn::make('created_at')
                ->label('Fecha'),
            ExportColumn::make('price_at_purchase')
                ->label('Precio Cobrado'),
            ExportColumn::make('service_cost_snapshot')
                ->label('Costo Base (Gasto)'),
            ExportColumn::make('service_price_snapshot')
                ->label('Precio de Lista (Valor Real)'),
            ExportColumn::make('profit')
                ->label('Utilidad')
                ->state(fn (Order $record): float => $record->price_at_purchase - $record->service_cost_snapshot),
            ExportColumn::make('discount')
                ->label('Descuento')
                ->state(fn (Order $record): float => $record->service_price_snapshot - $record->price_at_purchase),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $rowsCount = $export->successful_rows;
        $failedRowsCount = $export->getFailedRowsCount();

        $body = 'Tu reporte ha sido generado exitosamente. Se han exportado ' . number_format($rowsCount) . ' ' . ($rowsCount === 1 ? 'fila' : 'filas') . '.';

        if ($failedRowsCount > 0) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . ($failedRowsCount === 1 ? 'fila' : 'filas') . ' fallaron al procesarse.';
        }

        return $body;
    }
}
