@php($service = $getRecord())

@if($service && !$service->isAvailable())
    <div class="absolute inset-0 z-10 flex items-center justify-center rounded-xl bg-gray-900/60 backdrop-blur-sm">
        <div class="flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-800 shadow-lg">
            <x-heroicon-o-clock class="h-5 w-5" />
            <span>
                @if ($service->is_maintenance)
                    <strong class="block">En mantenimiento</strong>
                @endif
                {{ $service->unavailableReason() }}
            </span>
        </div>
    </div>
@endif
