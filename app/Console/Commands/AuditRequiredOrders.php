<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\DocMxService;
use Illuminate\Console\Command;

class AuditRequiredOrders extends Command
{
    protected $signature = 'orders:audit-required';

    protected $description = 'Audit required fields on existing orders without changing the database';

    public function handle(DocMxService $docMx): int
    {
        $rows = [];
        $summary = [];

        Order::query()
            ->with('service')
            ->orderBy('id')
            ->each(function (Order $order) use ($docMx, &$rows, &$summary): void {
                $serviceCode = $order->service?->code ?? (string) $order->service_id;
                $missingFields = $docMx->missingFields(
                    $serviceCode,
                    $order->service?->form_schema ?? [],
                    is_array($order->input_data) ? $order->input_data : []
                );

                if ($missingFields !== []) {
                    $rows[] = [
                        'order_id' => $order->id,
                        'service' => $serviceCode,
                        'status' => $order->status,
                        'missing_fields' => implode(', ', $missingFields),
                    ];
                    $summary[$serviceCode] = ($summary[$serviceCode] ?? 0) + 1;
                }
            });

        $this->table(
            ['order_id', 'service', 'status', 'missing_fields'],
            $rows
        );

        $this->newLine();
        $this->info('Rejected-by-validation summary:');
        $this->table(
            ['service', 'orders_that_would_be_rejected'],
            collect($summary)->map(fn (int $count, string $service): array => [$service, $count])->values()->all()
        );

        return self::SUCCESS;
    }
}
