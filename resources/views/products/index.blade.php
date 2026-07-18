@extends('layouts.public')

@section('title', 'SaaSNinja Applications Catalog')

@section('content')
    <!-- Hero Section -->
    <section class="bg-surface-container-lowest py-xl">
        <div class="max-w-7xl mx-auto px-margin-page">
            <div class="max-w-3xl">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-secondary-container text-on-secondary-fixed-variant text-label-sm font-label-sm mb-md">
                    Our Ecosystem
                </span>
                <h1 class="font-headline-xl text-headline-xl mb-sm">Products</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant leading-relaxed">
                    Discover our suite of high-performance tools engineered for elite engineering teams and digital-first enterprises. We focus on clarity, precision, and enterprise-grade reliability.
                </p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <div class="max-w-7xl mx-auto px-margin-page py-xl">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-xl">
            
            <!-- Sidebar Categories -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 shadow-sm">
                    <h3 class="font-label-md text-label-md text-on-surface mb-4 uppercase tracking-wider">Categories</h3>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('products.index') }}" class="flex items-center justify-between text-sm font-semibold p-2 rounded-lg bg-secondary-container text-on-secondary-fixed-variant">
                            <span>All Categories</span>
                        </a>
                        @foreach ($categories as $cat)
                            <div class="flex items-center justify-between text-sm font-medium text-on-surface-variant hover:text-primary p-2 transition-colors">
                                <span>{{ $cat->name }}</span>
                                <span class="text-xs bg-surface-container-high px-2 py-0.5 rounded-full font-bold text-outline">{{ $cat->products_count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Products Listing Grid -->
            <div class="lg:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-xl">
                    @forelse($products as $product)
                        <div class="group bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden transition-all duration-300 product-card-hover flex flex-col justify-between">
                            <div>
                                <div class="aspect-video overflow-hidden bg-surface-container relative">
                                    @if($product->image_url)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    @else
                                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=600" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    @endif
                                    <div class="absolute top-4 right-4">
                                        <x-badge color="indigo">{{ $product->category->name }}</x-badge>
                                    </div>
                                </div>
                                <div class="p-md lg:p-xl">
                                    <div class="flex items-center justify-between mb-sm">
                                        <h3 class="font-headline-md text-headline-md text-on-surface">{{ $product->name }}</h3>
                                        <span class="px-xs py-1 bg-surface-container-high rounded text-label-sm font-label-sm text-outline">v{{ $product->version }}</span>
                                    </div>
                                    <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                                        {{ $product->short_description }}
                                    </p>
                                </div>
                            </div>
                            <div class="px-md pb-md lg:px-xl lg:pb-xl">
                                <div class="flex flex-wrap items-center gap-md border-t border-outline-variant pt-md">
                                    <a class="font-label-md text-label-md text-primary hover:underline flex items-center gap-1" href="{{ route('products.show', $product->slug) }}">
                                        Details & Docs
                                        <span class="material-symbols-outlined text-sm">open_in_new</span>
                                    </a>
                                    @if($product->demo_url)
                                        <a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors" href="{{ $product->demo_url }}" target="_blank">Live Demo</a>
                                    @endif
                                    <a href="{{ $product->buy_url ?? '#' }}" target="_blank" class="ml-auto bg-primary text-on-primary px-md py-sm rounded-lg font-label-md text-label-md hover:bg-primary-container transition-all">Buy on Envato</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center text-on-surface-variant py-12">
                            No products found in the catalog.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection
