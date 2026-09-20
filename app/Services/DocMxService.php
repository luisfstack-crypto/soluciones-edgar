<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;

class DocMxService
{
    protected PendingRequest $client;

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
    public function createOrder(string $serviceId, array $formData, string $externalOrderId): array
    {
        return $this->client->post('/orders', [
            'service_id' => $serviceId,
            'form_data' => $formData,
            'external_order_id' => $externalOrderId,
        ])->json();
    }

    /**
     * Check the status of a specific order.
     */
    public function getOrder(string $docMxOrderId): array
    {
        return $this->client->get("/orders/{$docMxOrderId}")->json();
    }
}