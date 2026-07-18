@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/85 rounded-2xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden']) }}>
    @if($title || $slot->hasActualContent())
        @if($title)
            <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800">
                <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white">{{ $title }}</h3>
                @if($subtitle)
                    <p class="text-xs text-slate-500 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
        @endif
        
        <div class="p-6">
            {{ $slot }}
        </div>
    @else
        <div class="p-6">
            {{ $slot }}
        </div>
    @endif
</div>
