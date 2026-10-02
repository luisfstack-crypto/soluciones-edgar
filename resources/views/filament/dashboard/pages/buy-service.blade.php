<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 space-y-6">
             <!-- Service Card -->
             <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden ring-1 ring-gray-950/5 dark:ring-white/10">

                 <div class="p-6">
                     <h2 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">{{ $service->name }}</h2>
                     <div class="mb-4">
                         <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-lg font-bold text-green-700 ring-1 ring-inset ring-green-600/20">
                             ${{ number_format($service->price, 2) }} MXN
                         </span>
                         @if($service->suggested_price !== null)
                             <span class="ml-2 inline-flex items-center rounded-md bg-amber-50 px-2 py-1 text-sm font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                 Venta al público: ${{ number_format($service->suggested_price, 2) }} MXN
                             </span>
                         @endif
                     </div>
                     <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $service->description }}</p>
                     
                     <div class="border-t border-gray-100 dark:border-gray-700 pt-4">
                         <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Tiempo estimado</h3>
                         <p class="text-sm text-gray-500 dark:text-gray-400">{{ $service->processing_time ?? 'N/A' }}</p>
                     </div>
                     
                      <div class="border-t border-gray-100 dark:border-gray-700 pt-4 mt-4">
                         <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Horario</h3>
                         <p class="text-sm text-gray-500 dark:text-gray-400">{{ $service->active_schedule ?? 'N/A' }}</p>
                     </div>
                 </div>
             </div>
             
             <!-- Balance Card -->
             @php
                 $userBalance = (float) (auth()->user()->balance ?? 0);
                 $servicePrice = (float) ($service->price ?? 0);
                 $projectedBalance = $userBalance - $servicePrice;
             @endphp
             <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 ring-1 ring-gray-950/5 dark:ring-white/10">
                 <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tu Saldo Disponible</h3>
                 <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($userBalance, 2) }} MXN</p>
                 
                 <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700/60">
                     <div class="flex items-center justify-between flex-wrap gap-2">
                         <span class="text-sm font-medium {{ $projectedBalance < 0 ? 'text-danger-600 dark:text-danger-400 font-semibold' : 'text-gray-600 dark:text-gray-400' }}">
                             Saldo después de la compra: ${{ number_format($projectedBalance, 2) }} MXN
                         </span>
                         @if($projectedBalance < 0)
                             <span class="inline-flex items-center rounded-md bg-danger-50 px-2 py-0.5 text-xs font-semibold text-danger-700 ring-1 ring-inset ring-danger-600/20 dark:bg-danger-950/40 dark:text-danger-400 dark:ring-danger-500/30">
                                 Fondos insuficientes
                             </span>
                         @endif
                     </div>
                 </div>

                 @if($projectedBalance < 0)
                    <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded border border-red-200 dark:border-red-800">
                        <p class="text-sm text-red-600 dark:text-red-400">Saldo insuficiente. Por favor recarga tu billetera.</p>
                    </div>
                 @endif
             </div>
        </div>
        
        <div class="md:col-span-2">
            <form wire:submit="submit">
                {{ $this->form }}
                
                <div class="mt-6 flex justify-end">
                    <x-filament::button type="submit" size="xl" class="w-full md:w-auto">
                        Pagar y Contratar
                    </x-filament::button>
                </div>
            </form>
        </div>
    </div>
</x-filament-panels::page>
