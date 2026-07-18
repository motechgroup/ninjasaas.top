@extends('layouts.public')

@section('title', 'SaaSNinja Technical Documentation Portal')

@section('content')
    <!-- Hero Section -->
    <header class="bg-surface-container-lowest py-xl">
        <div class="max-w-7xl mx-auto px-margin-page text-center">
            <div class="inline-flex items-center gap-xs px-sm py-base bg-secondary-container text-on-secondary-fixed-variant rounded-full font-label-sm mb-md">
                <span class="material-symbols-outlined text-[16px] text-primary">menu_book</span>
                Developer Hub
            </div>
            <h1 class="font-headline-xl text-headline-xl mb-md max-w-3xl mx-auto">Documentation Hub</h1>
            <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto">Read user guides, server configuration manuals, and deployment steps for SaaSNinja software products.</p>
        </div>
    </header>

    <!-- Content Sections -->
    <div class="max-w-7xl mx-auto px-margin-page py-xl">
        <div class="space-y-12">
            @forelse($products as $product)
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 shadow-sm">
                    <div class="flex items-center gap-3 border-b border-outline-variant pb-4 mb-6">
                        <div class="w-10 h-10 rounded-lg bg-secondary-container text-on-secondary-fixed-variant flex items-center justify-center font-bold text-sm">
                            DOC
                        </div>
                        <div>
                            <h2 class="font-headline-md text-headline-md text-on-surface">{{ $product->name }} Documentation</h2>
                            <p class="text-body-sm text-body-sm text-on-surface-variant mt-0.5">{{ $product->short_description }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                        @forelse($product->docCategories as $cat)
                            <div class="space-y-3">
                                <h3 class="font-label-md text-label-md text-on-surface">{{ $cat->name }}</h3>
                                <ul class="space-y-2 text-body-sm text-body-sm text-on-surface-variant">
                                    @forelse($cat->articles as $art)
                                        <li>
                                            <a href="{{ route('docs.show', [$product->slug, $cat->slug, $art->slug]) }}" class="hover:text-primary transition-colors flex items-center gap-1.5 underline">
                                                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                                {{ $art->title }}
                                            </a>
                                        </li>
                                    @empty
                                        <li class="text-on-surface-variant/60 italic text-xs">No guides available.</li>
                                    @endforelse
                                </ul>
                            </div>
                        @empty
                            <div class="col-span-3 text-on-surface-variant/60 text-xs italic">No documentation sections generated for this product.</div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="text-center text-on-surface-variant py-12">
                    No technical documentation uploaded. Run seeders to populate docs.
                </div>
            @endforelse
        </div>
    </div>
@endsection
