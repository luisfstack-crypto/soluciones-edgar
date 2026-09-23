<div x-data="{ showCosto: false }">
    <button 
        x-show="!showCosto" 
        x-on:click="showCosto = true" 
        type="button" 
        class="flex items-center gap-1.5 text-sm font-medium px-2 py-1 rounded-md bg-gray-500/10 text-gray-700 ring-1 ring-inset ring-gray-500/20 transition hover:bg-gray-500/20 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/20"
    >
        <x-heroicon-o-eye class="w-4 h-4" />
        Ver Costo
    </button>

    <button 
        x-show="showCosto" 
        x-on:click="showCosto = false" 
        type="button" 
        class="flex items-center gap-1.5 text-sm font-medium px-2 py-1 rounded-md bg-success-500/10 text-success-700 ring-1 ring-inset ring-success-500/20 transition hover:bg-success-500/20 dark:bg-success-400/10 dark:text-success-400 dark:ring-success-400/20"
    >
        <x-heroicon-o-eye-slash class="w-4 h-4" />
        Costo: ${{ number_format((float) ($getState() ?? 0), 2) }} MXN
    </button>
</div>
