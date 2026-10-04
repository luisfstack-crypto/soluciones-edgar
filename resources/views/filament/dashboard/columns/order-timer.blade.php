@php
    $record = $getRecord();
@endphp

@if ($record)
    @php
        $isActive = in_array($record->status, ['pending', 'processing']);
        $isDelayed = $isActive && $record->is_delayed;
        $badgeClasses = $isDelayed
            ? 'text-warning-600 bg-warning-50 ring-warning-500/20 dark:text-warning-400 dark:bg-warning-950/40 dark:ring-warning-500/30'
            : 'text-success-600 bg-success-50 ring-success-500/20 dark:text-success-400 dark:bg-success-950/40 dark:ring-success-500/30';
    @endphp

    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg ring-1 ring-inset {{ $badgeClasses }}">
        <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span>
            @if ($isActive)
                Lleva {{ $record->elapsed_time_formatted }} · normalmente tarda {{ $record->service?->processing_time ?? 'N/A' }}
            @else
                {{ $record->elapsed_time_formatted }}
            @endif
        </span>
    </div>
@endif
