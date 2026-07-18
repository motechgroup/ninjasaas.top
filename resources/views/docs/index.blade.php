@extends('layouts.public')

@section('title', 'SaaSNinja Technical Documentation Portal')

@section('content')
    <!-- Hero Section -->
    <header class="bg-slate-50 dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 rounded-full text-xs font-semibold mb-6">
                <span class="material-symbols-outlined text-[16px] text-primary">menu_book</span>
                Developer Hub
            </div>
            <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl text-slate-900 dark:text-white mb-4 tracking-tight">Documentation Hub</h1>
            <p class="text-base sm:text-lg text-slate-500 max-w-2xl mx-auto">Read user guides, server configuration manuals, and deployment steps for SaaSNinja software products.</p>
        </div>
    </header>

    <!-- Content Sections -->
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="space-y-12">
            @forelse($products as $product)
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-4 border-b border-slate-100 dark:border-slate-800 pb-5 mb-6">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/40 text-primary flex items-center justify-center font-bold text-xs shadow-sm">
                            DOC
                        </div>
                        <div>
                            <h2 class="font-outfit font-bold text-xl sm:text-2xl text-slate-900 dark:text-white">{{ $product->name }} Documentation</h2>
                            <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ $product->short_description }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @forelse($product->docCategories as $cat)
                            <div class="space-y-4">
                                <h3 class="font-outfit font-bold text-sm text-slate-800 dark:text-slate-200 uppercase tracking-wider">{{ $cat->name }}</h3>
                                <ul class="space-y-3 text-sm">
                                    @forelse($cat->articles as $art)
                                        <li>
                                            <a href="{{ route('docs.show', [$product->slug, $cat->slug, $art->slug]) }}" class="text-slate-650 dark:text-slate-400 hover:text-primary dark:hover:text-primary transition-colors flex items-center gap-2 underline">
                                                <span class="material-symbols-outlined text-[16px] text-primary">menu_book</span>
                                                {{ $art->title }}
                                            </a>
                                        </li>
                                    @empty
                                        <li class="text-slate-400 italic text-xs">No guides available.</li>
                                    @endforelse
                                </ul>
                            </div>
                        @empty
                            <div class="col-span-3 text-slate-400 text-xs italic">No documentation sections generated for this product.</div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="text-center text-slate-500 py-12">
                    No technical documentation uploaded. Run seeders to populate docs.
                </div>
            @endforelse
        </div>
    </div>
@endsection
