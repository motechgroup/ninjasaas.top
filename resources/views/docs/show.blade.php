@extends('layouts.public')

@section('title', $article->title . ' - ' . $product->name . ' Docs')

@section('content')
    <div class="max-w-7xl mx-auto w-full px-6 py-12 flex flex-col lg:flex-row relative gap-8">
        
        <!-- Sidebar Navigation (left) -->
        <aside class="w-full lg:w-64 py-6 pr-6 lg:border-r border-slate-200 dark:border-slate-800 bg-transparent overflow-y-auto shrink-0">
            <div class="space-y-6">
                <section>
                    <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">Getting Started</h3>
                    <ul class="space-y-2">
                        <li>
                            <a class="flex items-center gap-2.5 px-3 py-2 text-primary font-bold border-l-2 border-primary bg-slate-50 dark:bg-slate-900/50 rounded-r-lg" href="{{ route('docs.index') }}">
                                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                                <span class="text-xs font-semibold">All Docs</span>
                            </a>
                        </li>
                    </ul>
                </section>

                @foreach($allCategories as $cat)
                    <section class="mt-6">
                        <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3">{{ $cat->name }}</h3>
                        <ul class="space-y-2">
                            @foreach($cat->articles as $art)
                                <li>
                                    <a class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs {{ $article->id === $art->id ? 'text-primary font-bold border-l-2 border-primary bg-slate-50 dark:bg-slate-900/50' : 'text-slate-600 dark:text-slate-400 hover:text-primary hover:bg-slate-50 dark:hover:bg-slate-900/40' }}" 
                                       href="{{ route('docs.show', [$product->slug, $cat->slug, $art->slug]) }}">
                                        <span class="material-symbols-outlined text-[18px] flex-shrink-0">menu_book</span>
                                        <span class="truncate">{{ $art->title }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endforeach
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 min-w-0 py-6 lg:px-8">
            
            <!-- Breadcrumbs -->
            <nav aria-label="Breadcrumb" class="flex items-center text-xs font-medium text-slate-500 dark:text-slate-400 mb-6">
                <a class="hover:text-primary transition-colors" href="{{ route('docs.index') }}">Documentation</a>
                <span class="material-symbols-outlined text-[16px] mx-1 text-slate-400">chevron_right</span>
                <span class="text-slate-500">{{ $product->name }}</span>
                <span class="material-symbols-outlined text-[16px] mx-1 text-slate-400">chevron_right</span>
                <span class="text-slate-900 dark:text-white font-bold">{{ $article->title }}</span>
            </nav>

            <!-- Title Section -->
            <div class="mb-8">
                <h1 class="font-outfit font-extrabold text-3xl sm:text-4xl text-slate-900 dark:text-white mb-2 tracking-tight">{{ $article->title }}</h1>
                <p class="text-xs text-slate-500 max-w-3xl leading-relaxed flex items-center gap-1">
                    <span>Product:</span> <strong class="text-slate-700 dark:text-slate-300 font-semibold">{{ $product->name }}</strong> 
                    <span class="text-slate-350 dark:text-slate-700">&bull;</span> 
                    <span>Category:</span> <strong class="text-slate-700 dark:text-slate-300 font-semibold">{{ $category->name }}</strong>
                </p>
            </div>

            <!-- Content section with styled prose elements -->
            <section class="prose prose-slate dark:prose-invert max-w-none mb-10">
                <div class="text-slate-650 dark:text-slate-300 text-sm space-y-6 leading-relaxed">
                    {!! $article->content !!}
                </div>
            </section>

            <!-- Visual Note Box -->
            <div class="flex items-start gap-4 p-5 bg-blue-50/70 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/30 rounded-2xl mb-10">
                <span class="material-symbols-outlined text-blue-500 text-[26px] mt-0.5">info</span>
                <div>
                    <p class="text-blue-950 dark:text-blue-300 font-bold text-xs uppercase tracking-wider mb-1">Support Workspace</p>
                    <p class="text-blue-800 dark:text-blue-400 text-xs leading-relaxed">
                        If you encounter configuration difficulties, feel free to open a support ticket in the <a class="underline font-bold text-primary hover:text-primary-hover" href="{{ route('dashboard') }}">Customer Helpdesk Workspace</a>.
                    </p>
                </div>
            </div>
        </main>

        <!-- Right Content Outline (On this page) -->
        <aside class="hidden xl:block w-60 sticky top-24 h-fit py-6 pl-6 shrink-0 border-l border-slate-100 dark:border-slate-800">
            <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4">On this page</h4>
            <ul class="space-y-3 text-xs font-semibold text-slate-500 dark:text-slate-400">
                <li><a class="text-primary border-l-2 border-primary pl-3 block" href="#">Overview</a></li>
                <li><a class="hover:text-primary border-l-2 border-transparent hover:border-primary pl-3 block transition-colors" href="#">Requirements</a></li>
                <li><a class="hover:text-primary border-l-2 border-transparent hover:border-primary pl-3 block transition-colors" href="#">Setup Steps</a></li>
            </ul>
        </aside>

    </div>
@endsection
