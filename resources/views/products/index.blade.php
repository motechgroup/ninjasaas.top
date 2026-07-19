@extends('layouts.public')

@section('title', 'SaaSNinja Applications Catalog')

@section('content')
    <!-- Hero Section -->
    <section class="bg-slate-50 dark:bg-slate-950 py-16 border-b border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-3xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 text-xs font-semibold mb-4">
                    Our Ecosystem
                </span>
                <h1 class="font-outfit font-extrabold text-4xl sm:text-5xl text-slate-900 dark:text-white mb-4 tracking-tight">Products Catalog</h1>
                <p class="text-base sm:text-lg text-slate-500 leading-relaxed">
                    Discover our suite of high-performance tools engineered for elite engineering teams and digital-first enterprises. We focus on clarity, precision, and enterprise-grade reliability.
                </p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Sidebar Categories -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
                    <h3 class="font-outfit font-bold text-xs text-slate-400 mb-4 uppercase tracking-wider">Categories</h3>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('products.index') }}" class="flex items-center justify-between text-sm font-bold p-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400">
                            <span>All Categories</span>
                        </a>
                        @foreach ($categories as $cat)
                            <div class="flex items-center justify-between text-sm font-medium text-slate-655 hover:text-primary p-2 transition-colors">
                                <span>{{ $cat->name }}</span>
                                <span class="text-xs bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-full font-bold text-slate-500">{{ $cat->products_count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Products Listing Grid -->
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($products as $product)
                        <a href="{{ route('products.show', $product->slug) }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden transition-all duration-300 shadow-sm hover:shadow-md hover:scale-[1.01] flex flex-col justify-between relative cursor-pointer no-underline text-inherit">
                            <div>
                                <div class="aspect-video overflow-hidden bg-slate-100 dark:bg-slate-950 relative">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=600" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-102">
                                    @endif
                                    <div class="absolute top-4 right-4">
                                        <span class="inline-flex items-center px-2.5 py-1 bg-white/90 dark:bg-slate-900/90 text-[10px] font-bold uppercase tracking-wider rounded-lg shadow-sm border border-slate-100 dark:border-slate-800 text-slate-800 dark:text-slate-200">
                                            {{ $product->category->name }}
                                        </span>
                                    </div>
                                </div>
                                <div class="p-6 space-y-3">
                                    <div class="flex items-center justify-between gap-4">
                                        <h3 class="font-outfit font-bold text-lg text-slate-900 dark:text-white leading-snug">{{ $product->name }}</h3>
                                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-md text-xs font-bold text-slate-500 whitespace-nowrap">v{{ $product->version }}</span>
                                    </div>
                                    <p class="text-sm text-slate-500 leading-relaxed">
                                        {{ $product->short_description }}
                                    </p>
                                </div>
                            </div>
                            
                            @if($product->demo_url)
                                <div class="px-6 pb-6 relative z-10">
                                    <div class="flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 dark:border-slate-800 pt-4">
                                        <span onclick="event.preventDefault(); event.stopPropagation(); window.open('{{ $product->demo_url }}', '_blank')"
                                              class="text-xs font-semibold text-slate-500 hover:text-primary transition-colors flex items-center gap-0.5 cursor-pointer">
                                            Demo
                                            <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </a>
                    @empty
                        <div class="col-span-2 text-center text-slate-500 py-12">
                            No products found in the catalog.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection
