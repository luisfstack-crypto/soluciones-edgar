<div class="flex items-center px-3 py-1 mr-4 text-sm font-medium text-green-700 bg-green-100 border border-green-200 rounded-full dark:bg-green-900/30 dark:text-green-400 dark:border-green-800">
    <x-heroicon-o-banknotes class="w-4 h-4 mr-2"/>
    <span>Saldo: ${{ number_format(auth()->user()->balance ?? 0, 2) }}</span>
</div>
