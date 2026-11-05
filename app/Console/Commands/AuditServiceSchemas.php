<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Services\DocMxService;
use Illuminate\Console\Command;

class AuditServiceSchemas extends Command
{
    protected $signature = 'services:audit-schema';

    protected $description = 'Audit service form schemas without changing the database';

    public function handle(DocMxService $docMx): int
    {
        $rows = [];

        Service::query()
            ->orderBy('code')
            ->each(function (Service $service) use ($docMx, &$rows): void {
                foreach ($service->form_schema ?? [] as $field) {
                    $name = (string) ($field['name'] ?? '');
                    $canonicalName = $docMx->canonicalFieldName($service->code, $name);

                    $rows[] = [
                        'service' => $service->code,
                        'field' => $name,
                        'required' => ($field['required'] ?? false) ? 'yes' : 'no',
                        'type' => $field['type'] ?? 'text',
                        'name_warning' => $canonicalName !== $name
                            ? "expected {$canonicalName}"
                            : '',
                    ];
                }
            });

        $this->table(
            ['service', 'field', 'required', 'type', 'name_warning'],
            $rows
        );

        return self::SUCCESS;
    }
}
