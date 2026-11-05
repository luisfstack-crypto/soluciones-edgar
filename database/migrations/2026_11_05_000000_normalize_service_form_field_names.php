<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $canonicalFields = [
            'csf-curp-clon' => ['curp', 'lugarEmision'],
        ];

        DB::table('services')
            ->whereIn('code', array_keys($canonicalFields))
            ->orderBy('id')
            ->get(['id', 'code', 'form_schema'])
            ->each(function (object $service) use ($canonicalFields): void {
                $canonicalNames = $canonicalFields[$service->code];
                $originalSchema = json_decode($service->form_schema ?? '[]', true) ?: [];
                $schema = collect($originalSchema)
                    ->map(function (array $field) use ($canonicalNames): array {
                        foreach ($canonicalNames as $canonicalName) {
                            if (strcasecmp((string) ($field['name'] ?? ''), $canonicalName) === 0) {
                                return [...$field, 'name' => $canonicalName];
                            }
                        }

                        return $field;
                    })
                    ->all();

                if ($schema !== $originalSchema) {
                    DB::table('services')
                        ->where('id', $service->id)
                        ->update(['form_schema' => json_encode($schema, JSON_UNESCAPED_UNICODE)]);
                }
            });
    }

    public function down(): void
    {
        // Field-name normalization is intentionally not reversible.
    }
};
