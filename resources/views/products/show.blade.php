@extends('layouts.public')

@section('title', $product->name . ' - Product Details')

@section('content')
    <!-- Product Header Banner -->
    <div class="py-16 bg-slate-900 text-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-3 text-center md:text-left">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border border-indigo-500/30 bg-indigo-500/10 text-xs font-bold uppercase tracking-wider text-indigo-400">
                        {{ $product->category->name }}
                    </div>
                    <h1 class="font-outfit font-extrabold text-3xl sm:text-4xl tracking-tight leading-none">{{ $product->name }}</h1>
                    <p class="text-slate-400 text-sm max-w-xl font-medium">{{ $product->short_description }}</p>
                </div>
                
                <div class="flex items-center gap-4">
                    @if($product->demo_url)
                        <a href="{{ $product->demo_url }}" target="_blank" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 font-bold rounded-xl text-sm transition-all">
                            Live Demo
                        </a>
                    @endif
                    <a href="{{ $product->buy_url ?? '#' }}" target="_blank" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 font-bold rounded-xl text-sm shadow-lg shadow-indigo-500/20 transition-all">
                        Buy on CodeCanyon
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Navigation Tabs -->
    <div class="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 sticky top-16 z-30" x-data="{ tab: 'features' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-6 overflow-x-auto h-12">
                <button @click="tab = 'features'" :class="tab === 'features' ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-500 dark:text-slate-400 font-medium'" class="h-full px-2 text-sm transition-colors duration-150">
                    Key Features
                </button>
                <button @click="tab = 'screenshots'" :class="tab === 'screenshots' ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-500 dark:text-slate-400 font-medium'" class="h-full px-2 text-sm transition-colors duration-150">
                    Screenshots
                </button>
                <button @click="tab = 'versions'" :class="tab === 'versions' ? 'border-b-2 border-indigo-600 text-indigo-600 dark:text-indigo-400 font-bold' : 'text-slate-500 dark:text-slate-400 font-medium'" class="h-full px-2 text-sm transition-colors duration-150">
                    Versions & Changelogs
                </button>
            </div>
        </div>

        <!-- Tab Contents -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            
            <!-- Features Tab -->
            <div x-show="tab === 'features'" class="space-y-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                    <div>
                        <h2 class="font-outfit font-extrabold text-2xl text-slate-950 dark:text-white mb-4">Description</h2>
                        <div class="text-slate-600 dark:text-slate-350 text-sm leading-relaxed whitespace-pre-wrap">
                            {{ $product->description }}
                        </div>
                    </div>
                    
                    @if($product->image_url)
                        <div class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-lg">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full object-cover">
                        </div>
                    @endif
                </div>

                <div class="pt-8 border-t border-slate-200 dark:border-slate-800">
                    <h3 class="font-outfit font-extrabold text-xl text-slate-950 dark:text-white mb-6">Key Specifications & Features</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($product->features as $feature)
                            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 p-5 rounded-2xl shadow-sm">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 dark:bg-indigo-950/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <h4 class="font-outfit font-bold text-base text-slate-900 dark:text-white mb-2">{{ $feature->title }}</h4>
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">{{ $feature->description }}</p>
                            </div>
                        @empty
                            <div class="col-span-3 text-slate-500 text-sm">No specifications mapped yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Screenshots Tab -->
            <div x-show="tab === 'screenshots'" style="display: none;" class="space-y-6">
                <h3 class="font-outfit font-extrabold text-xl text-slate-950 dark:text-white mb-4">Product Interface Showcase</h3>
                
                @if($product->screenshots->isEmpty())
                    <p class="text-slate-500 text-sm">No interface screenshots uploaded yet.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        @foreach($product->screenshots as $screen)
                            <div class="border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm bg-white dark:bg-slate-900">
                                <img src="{{ $screen->image_url }}" alt="{{ $screen->caption ?? $product->name }}" class="w-full object-cover max-h-72">
                                @if($screen->caption)
                                    <div class="p-3 border-t border-slate-100 dark:border-slate-800 text-xs font-semibold text-slate-600 dark:text-slate-300">
                                        {{ $screen->caption }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Versions Tab -->
            <div x-show="tab === 'versions'" style="display: none;" class="space-y-8">
                <h3 class="font-outfit font-extrabold text-xl text-slate-950 dark:text-white">Product Lifecycle Changelogs</h3>
                
                @if($product->versions->isEmpty())
                    <p class="text-slate-500 text-sm">No version history logged yet.</p>
                @else
                    <div class="space-y-8">
                        @foreach($product->versions as $v)
                            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800/80 rounded-2xl p-6 shadow-sm">
                                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3 mb-4">
                                    <div class="flex items-center gap-3">
                                        <span class="font-outfit font-extrabold text-lg text-slate-950 dark:text-white">Version {{ $v->version }}</span>
                                        @if($v->is_stable)
                                            <x-badge color="green">Stable</x-badge>
                                        @endif
                                    </div>
                                    <span class="text-xs font-semibold text-slate-400">{{ $v->release_date->format('F d, Y') }}</span>
                                </div>

                                <div class="space-y-4">
                                    @forelse($v->changelogs as $change)
                                        <div class="flex gap-3 text-sm">
                                            <div class="flex-shrink-0 mt-0.5">
                                                @if($change->type === 'added')
                                                    <x-badge color="green">Added</x-badge>
                                                @elseif($change->type === 'changed')
                                                    <x-badge color="indigo">Changed</x-badge>
                                                @elseif($change->type === 'fixed')
                                                    <x-badge color="yellow">Fixed</x-badge>
                                                @elseif($change->type === 'security')
                                                    <x-badge color="red">Security</x-badge>
                                                @else
                                                    <x-badge color="gray">Removed</x-badge>
                                                @endif
                                            </div>
                                            <div class="text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
                                                {{ $change->description }}
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400">No detailed change logs documented for this release.</p>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
