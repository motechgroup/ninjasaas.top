@props(['items' => []])

<nav class="flex text-xs font-semibold text-slate-500 mb-6 uppercase tracking-wider" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
        <li class="inline-flex items-center">
            <a href="{{ route('home') }}" class="inline-flex items-center text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                Home
            </a>
        </li>
        @foreach($items as $label => $url)
            <li>
                <div class="flex items-center">
                    <svg class="w-3.5 h-3.5 text-slate-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    @if($loop->last || empty($url))
                        <span class="text-slate-400 dark:text-slate-500 select-none ml-1 md:ml-2">{{ $label }}</span>
                    @else
                        <a href="{{ $url }}" class="text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors ml-1 md:ml-2">{{ $label }}</a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
