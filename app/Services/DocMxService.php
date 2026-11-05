<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

class DocMxService
{
    public const CANONICAL_FIELDS = [
        'csf-curp-clon' => ['curp', 'lugarEmision'],
        'recibo-cfe' => ['cfe_number', 'nombre_titular', 'total_pagar'],
    ];

    public const FIELD_MAP = [
        'recibo-cfe' => [
            'cfe_number' => 'numeroServicioCfe',
            'nombre_titular' => 'nombreTitularCfe',
            'total_pagar' => 'totalPagarCfe',
        ],
    ];

    protected PendingRequest $client;

    public function canonicalFields(string $serviceCode): array
    {
        return self::CANONICAL_FIELDS[$serviceCode] ?? [];
    }

    public function canonicalFieldName(string $serviceCode, string $fieldName): string
    {
        foreach ($this->canonicalFields($serviceCode) as $canonicalField) {
            if (strcasecmp($canonicalField, $fieldName) === 0) {
                return $canonicalField;
            }
        }

        return $fieldName;
    }

    public function normalizeFormData(string $serviceCode, array $formData): array
    {
        $normalized = $formData;

        foreach ($this->canonicalFields($serviceCode) as $canonicalField) {
            foreach (array_keys($formData) as $fieldName) {
                if (strcasecmp($canonicalField, (string) $fieldName) !== 0) {
                    continue;
                }

                $normalized[$canonicalField] = $formData[$fieldName];

                if ($fieldName !== $canonicalField) {
                    unset($normalized[$fieldName]);
                }

                break;
            }
        }

        return $normalized;
    }

    public function buildFormData(string $serviceCode, array $formData): array
    {
        $formData = $this->normalizeFormData($serviceCode, $formData);
        $fieldMap = self::FIELD_MAP[$serviceCode] ?? [];
        $mapped = [];

        foreach ($formData as $fieldName => $value) {
            $docMxFieldName = $fieldMap[$fieldName] ?? $fieldName;

            if ($serviceCode === 'recibo-cfe' && $fieldName === 'nombre_titular') {
                $value = mb_strtoupper(trim((string) $value));
            }

            $mapped[$docMxFieldName] = (string) $value;
        }

        return $mapped;
    }

    public function requiredFields(string $serviceCode, array $formSchema): array
    {
        $requiredFields = collect($formSchema)
            ->filter(function (array $field): bool {
                if (! ($field['required'] ?? false) || ($field['type'] ?? 'text') === 'file') {
                    return false;
                }

                return ! empty($field['name']);
            })
            ->pluck('name')
            ->map(fn (string $field): string => $this->canonicalFieldName($serviceCode, $field))
            ->values();

        foreach ($formSchema as $field) {
            if ($this->canonicalFieldName($serviceCode, (string) ($field['name'] ?? '')) === 'lugarEmision') {
                $requiredFields->push('lugarEmision');
            }
        }

        foreach ($this->canonicalFields($serviceCode) as $canonicalField) {
            $requiredFields->push($canonicalField);
        }

        return $requiredFields->unique()->values()->all();
    }

    public function missingFields(string $serviceCode, array $formSchema, array $formData): array
    {
        $normalized = $this->normalizeFormData($serviceCode, $formData);

        return collect($this->requiredFields($serviceCode, $formSchema))
            ->filter(fn (string $field): bool => blank(data_get($normalized, $field)))
            ->values()
            ->all();
    }

    public function __construct()
    {
        $this->client = Http::withHeaders([
            'X-API-Key' => env('DOCMX_API_KEY'),
            'ngrok-skip-browser-warning' => 'true', 
            'Accept' => 'application/json',
        ])->baseUrl(env('DOCMX_BASE_URL') . '/api/b2b');
    }

    /**
     * Check the current prepaid balance.
     */
    public function getBalance(): array
    {
        return $this->client->get('/balance')->json();
    }

    /**
     * Fetch the available services catalog with live prices.
     */
    public function getCatalog(): array
    {
        return $this->client->get('/services')->json();
    }

    /**
     * Create a new order in DocMX.
     */
    public function createOrder(string $serviceId, array $formData, string $externalOrderId): Response
    {
        return $this->client->post('/orders', [
            'service_id' => $serviceId,
            'form_data' => $formData,
            'external_order_id' => $externalOrderId,
        ]);
    }

    /**
     * Check the status of a specific order.
     */
    public function getOrder(string $docMxOrderId): array
    {
        return $this->client->get("/orders/{$docMxOrderId}")->json();
    }
}