@php
    $banners = \App\Models\Banner::where('is_active', true)->orderBy('sort_order', 'asc')->get();
@endphp

@if($banners->isNotEmpty())
    <div class="flex flex-col gap-4 mb-8">
        @foreach($banners as $banner)
            @php
                $bgClass = match($banner->type) {
                    'info' => 'bg-blue-500/10 border-blue-500/20 text-blue-700 dark:text-blue-400',
                    'success' => 'bg-green-500/10 border-green-500/20 text-green-700 dark:text-green-400',
                    'warning' => 'bg-yellow-500/10 border-yellow-500/20 text-yellow-700 dark:text-yellow-400',
                    'danger' => 'bg-red-500/10 border-red-500/20 text-red-700 dark:text-red-400',
                    default => 'bg-gray-500/10 border-gray-500/20 text-gray-700 dark:text-gray-400',
                };
                $iconClass = match($banner->type) {
                    'info' => 'text-blue-500',
                    'success' => 'text-green-500',
                    'warning' => 'text-yellow-500',
                    'danger' => 'text-red-500',
                    default => 'text-gray-500',
                };
            @endphp
            <div class="{{ $bgClass }} border rounded-xl p-4 flex items-start sm:items-center gap-4 shadow-sm w-full">
                <div class="flex-shrink-0 {{ $iconClass }}">
                    @if($banner->type === 'info')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @elseif($banner->type === 'success')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @elseif($banner->type === 'warning')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    @endif
                </div>
                <div class="flex-1 w-full prose dark:prose-invert max-w-none text-sm">
                    @if($banner->title)
                        <strong class="block mb-1 text-base">{{ $banner->title }}</strong>
                    @endif
                    <div>{!! $banner->content !!}</div>
                </div>
            </div>
        @endforeach
    </div>
@endif
