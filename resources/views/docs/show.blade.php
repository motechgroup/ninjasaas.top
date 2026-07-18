@extends('layouts.public')

@section('title', $article->title . ' - ' . $product->name . ' Docs')

@section('content')
    <div class="max-w-7xl mx-auto w-full px-margin-page py-lg flex flex-col md:flex-row relative gap-md">
        
        <!-- Sidebar Navigation (left) -->
        <aside class="w-full md:w-64 py-md pr-md md:border-r border-outline-variant bg-surface overflow-y-auto shrink-0">
            <div class="space-y-lg">
                <section>
                    <h3 class="text-label-sm font-label-sm text-outline uppercase tracking-widest mb-sm">Getting Started</h3>
                    <ul class="space-y-xs">
                        <li>
                            <a class="flex items-center gap-xs px-xs py-base text-primary font-bold border-l-2 border-primary bg-surface-container-low rounded-r-lg" href="{{ route('docs.index') }}">
                                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                                <span class="text-body-sm font-body-sm">All Docs</span>
                            </a>
                        </li>
                    </ul>
                </section>

                @foreach($allCategories as $cat)
                    <section class="mt-md">
                        <h3 class="text-label-sm font-label-sm text-outline uppercase tracking-widest mb-sm">{{ $cat->name }}</h3>
                        <ul class="space-y-xs">
                            @foreach($cat->articles as $art)
                                <li>
                                    <a class="flex items-center gap-xs px-xs py-base rounded-lg transition-all {{ $article->id === $art->id ? 'text-primary font-bold border-l-2 border-primary bg-surface-container-low' : 'text-on-surface-variant hover:text-primary hover:bg-surface-container-lowest' }}" 
                                       href="{{ route('docs.show', [$product->slug, $cat->slug, $art->slug]) }}">
                                        <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                        <span class="text-body-sm font-body-sm">{{ $art->title }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endforeach
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 min-w-0 py-md md:px-lg">
            
            <!-- Breadcrumbs -->
            <nav aria-label="Breadcrumb" class="flex items-center text-label-sm font-label-sm text-on-surface-variant mb-md">
                <a class="hover:text-primary transition-colors" href="{{ route('docs.index') }}">Documentation</a>
                <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                <span class="text-on-surface-variant">{{ $product->name }}</span>
                <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                <span class="text-on-surface">{{ $article->title }}</span>
            </nav>

            <!-- Title Section -->
            <div class="mb-xl">
                <h1 class="font-headline-xl text-headline-xl text-on-surface mb-sm">{{ $article->title }}</h1>
                <p class="text-body-lg font-body-lg text-on-surface-variant max-w-3xl leading-relaxed">
                    Product: <strong>{{ $product->name }}</strong> &bull; Category: <strong>{{ $category->name }}</strong>
                </p>
            </div>

            <!-- Content section with styled prose elements -->
            <section class="prose max-w-none mb-xl">
                <div class="text-on-surface-variant font-body-md text-body-md space-y-6 leading-relaxed">
                    {!! $article->content !!}
                </div>
            </section>

            <!-- Visual Note Box -->
            <div class="flex items-start gap-md p-md bg-secondary-container rounded-xl mb-xl">
                <span class="material-symbols-outlined text-primary text-[28px]">info</span>
                <div>
                    <p class="text-on-secondary-fixed-variant font-label-md text-label-md mb-xs">Support Workspace</p>
                    <p class="text-on-secondary-fixed-variant font-body-sm text-body-sm">
                        If you encounter configuration difficulties, feel free to open a support ticket in the <a class="underline font-bold text-primary" href="{{ route('dashboard') }}">Customer Helpdesk Workspace</a>.
                    </p>
                </div>
            </div>
        </main>

        <!-- Right Content Outline (On this page) -->
        <aside class="hidden xl:block w-56 sticky top-16 h-fit py-md pl-md shrink-0">
            <h4 class="text-label-sm font-label-sm text-on-surface mb-sm font-bold">On this page</h4>
            <ul class="space-y-sm text-body-sm font-body-sm text-on-surface-variant">
                <li><a class="hover:text-primary border-l-2 border-transparent hover:border-primary pl-xs transition-all" href="#">Overview</a></li>
                <li><a class="hover:text-primary border-l-2 border-transparent hover:border-primary pl-xs transition-all" href="#">Requirements</a></li>
                <li><a class="hover:text-primary border-l-2 border-transparent hover:border-primary pl-xs transition-all" href="#">Setup Steps</a></li>
            </ul>
        </aside>

    </div>
@endsection
