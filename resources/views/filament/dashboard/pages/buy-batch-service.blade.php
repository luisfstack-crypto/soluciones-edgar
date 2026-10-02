<x-filament-panels::page>
    @php
        $totalItems = count($data['requests'] ?? []);
        $servicePrice = (float) $service->price;
        $totalCost = $totalItems * $servicePrice;
        $userBalance = (float) (auth()->user()->balance ?? 0);
        $projectedBalance = $userBalance - $totalCost;
    @endphp

    <form wire:submit="submit" class="space-y-6">
        {{ $this->form }}

        <section class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <h2 class="text-base font-semibold text-gray-950 dark:text-white">Resumen del lote</h2>
            <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Total de solicitudes</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-950 dark:text-white">{{ $totalItems }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Costo total</dt>
                    <dd class="mt-1 text-lg font-semibold text-gray-950 dark:text-white">${{ number_format($totalCost, 2) }} MXN</dd>
                </div>
                <div>
                    <dt class="text-sm text-gray-500 dark:text-gray-400">Saldo proyectado</dt>
                    <dd class="mt-1 text-lg font-semibold {{ $projectedBalance < 0 ? 'text-danger-600 dark:text-danger-400' : 'text-gray-950 dark:text-white' }}">
                        ${{ number_format($projectedBalance, 2) }} MXN
                    </dd>
                    @if ($projectedBalance < 0)
                        <p class="mt-1 text-sm font-medium text-danger-600 dark:text-danger-400">Fondos insuficientes</p>
                    @endif
                </div>
            </dl>
        </section>

        <div class="flex justify-end">
            <x-filament::button type="submit" size="lg" :disabled="$projectedBalance < 0">
                Enviar lote
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>