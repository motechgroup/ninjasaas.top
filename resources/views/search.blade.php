@extends('layouts.public')

@section('title', 'Search Results for "' . $query . '" - SaaSNinja')

@section('content')
    @php
        $highlight = function($text, $query) {
            if (!$query) return e($text);
            $escaped = e($text);
            $escapedQuery = e($query);
            return preg_replace('/(' . preg_quote($escapedQuery, '/') . ')/i', '<mark class="bg-yellow-200 dark:bg-yellow-900/60 text-slate-900 dark:text-white px-1 py-0.5 rounded font-semibold">$1</mark>', $escaped);
        };
    @endphp

    <!-- Search Hero Header -->
    <section class="bg-slate-50 dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-4xl mx-auto px-6 text-center space-y-6">
            <h1 class="font-outfit font-extrabold text-3xl sm:text-4xl text-slate-950 dark:text-white tracking-tight">
                @if($query !== '')
                    Found {{ $totalResults }} {{ Str::plural('result', $totalResults) }} for "{{ $query }}"
                @else
                    Search SaaSNinja Software
                @endif
            </h1>
            
            <form action="{{ route('search') }}" method="GET" class="max-w-xl mx-auto relative flex items-center">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ $query }}" 
                    placeholder="Search products, services, documentation, blogs..." 
                    class="w-full px-5 py-3 pr-12 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-sm transition-all text-slate-800 dark:text-slate-200"
                >
                <button type="submit" class="absolute right-3.5 text-slate-400 hover:text-primary transition-colors flex items-center">
                    <span class="material-symbols-outlined text-[22px]">search</span>
                </button>
            </form>
        </div>
    </section>

    <!-- Results Block -->
    <section class="py-16 bg-white dark:bg-slate-900 min-h-[400px]">
        <div class="max-w-4xl mx-auto px-6">
            
            @if($totalResults === 0)
                <div class="text-center py-12 space-y-4">
                    <span class="material-symbols-outlined text-slate-300 text-6xl">search_off</span>
                    <h2 class="font-outfit font-bold text-lg text-slate-700 dark:text-slate-400">No results matched your search</h2>
                    <p class="text-slate-400 text-xs sm:text-sm max-w-md mx-auto leading-relaxed">
                        Try checking spelling, removing filters, or searching for broader terms like "CRM", "deployment", or "Laravel".
                    </p>
                </div>
            @else
                
                <div class="space-y-12">
                    <!-- Products -->
                    @if($products->isNotEmpty())
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 dark:border-slate-800/80 pb-2">
                                Software Products ({{ $products->count() }})
                            </h3>
                            <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                @foreach($products as $prod)
                                    <div class="py-4 first:pt-0 last:pb-0 flex items-start gap-4">
                                        <div class="w-10 h-10 rounded bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-[20px]">layers</span>
                                        </div>
                                        <div class="space-y-1">
                                            <a href="{{ route('products.show', $prod->slug) }}" class="font-outfit font-bold text-sm sm:text-base text-slate-900 dark:text-white hover:text-primary transition-colors block">
                                                {!! $highlight($prod->name, $query) !!}
                                            </a>
                                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                                {!! $highlight($prod->short_description, $query) !!}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Services -->
                    @if($services->isNotEmpty())
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 dark:border-slate-800/80 pb-2">
                                Professional Services ({{ $services->count() }})
                            </h3>
                            <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                @foreach($services as $serv)
                                    <div class="py-4 first:pt-0 last:pb-0 flex items-start gap-4">
                                        <div class="w-10 h-10 rounded bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-[20px]">construction</span>
                                        </div>
                                        <div class="space-y-1">
                                            <a href="{{ route('services.show', $serv->slug) }}" class="font-outfit font-bold text-sm sm:text-base text-slate-900 dark:text-white hover:text-primary transition-colors block">
                                                {!! $highlight($serv->name, $query) !!}
                                            </a>
                                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                                {!! $highlight($serv->short_description, $query) !!}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Documentation -->
                    @if($articles->isNotEmpty())
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 dark:border-slate-800/80 pb-2">
                                Documentation Articles ({{ $articles->count() }})
                            </h3>
                            <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                @foreach($articles as $art)
                                    <div class="py-4 first:pt-0 last:pb-0 flex items-start gap-4">
                                        <div class="w-10 h-10 rounded bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-[20px]">menu_book</span>
                                        </div>
                                        <div class="space-y-1">
                                            @if($art->category && $art->category->product)
                                                <a href="{{ route('docs.show', [$art->category->product->slug, $art->category->slug, $art->slug]) }}" class="font-outfit font-bold text-sm sm:text-base text-slate-900 dark:text-white hover:text-primary transition-colors block">
                                                    {!! $highlight($art->title, $query) !!}
                                                </a>
                                            @else
                                                <span class="font-outfit font-bold text-sm sm:text-base text-slate-900 dark:text-white block">
                                                    {!! $highlight($art->title, $query) !!}
                                                </span>
                                            @endif
                                            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                                                @if($art->category && $art->category->product)
                                                    {{ $art->category->product->name }} / {{ $art->category->name }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Blog Posts -->
                    @if($posts->isNotEmpty())
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 dark:border-slate-800/80 pb-2">
                                Blog Posts ({{ $posts->count() }})
                            </h3>
                            <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                                @foreach($posts as $post)
                                    <div class="py-4 first:pt-0 last:pb-0 flex items-start gap-4">
                                        <div class="w-10 h-10 rounded bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                                            <span class="material-symbols-outlined text-[20px]">rss_feed</span>
                                        </div>
                                        <div class="space-y-1">
                                            <a href="{{ route('blog.show', $post->slug) }}" class="font-outfit font-bold text-sm sm:text-base text-slate-900 dark:text-white hover:text-primary transition-colors block">
                                                {!! $highlight($post->title, $query) !!}
                                            </a>
                                            <p class="text-xs text-slate-500 leading-relaxed line-clamp-2">
                                                {!! $highlight($post->summary, $query) !!}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

            @endif

        </div>
    </section>
@endsection
