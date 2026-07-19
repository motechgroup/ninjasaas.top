@extends('layouts.public')

@section('title', 'SaaSNinja - Premium Software & Envato Customer Portal')

@section('content')
    <!-- Hero Section -->
    <header class="relative pt-16 pb-12 overflow-hidden bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-margin-page grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
            
            <!-- Left Column Content -->
            <div class="lg:col-span-6 space-y-6 text-left">
                <h1 class="font-outfit font-extrabold text-headline-xl-mobile md:text-headline-xl text-on-surface leading-tight">
                    We Build Powerful <br class="hidden sm:inline">
                    Software <span class="bg-gradient-to-r from-primary to-surface-tint bg-clip-text text-transparent">Solutions</span> <br>
                    for Modern Businesses
                </h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant max-w-xl leading-relaxed">
                    SaaSNinja is a software development company creating high-quality web applications, systems and digital solutions that help businesses work smarter and grow faster.
                </p>
                <div class="flex flex-wrap gap-4 pt-2">
                    <a href="{{ route('products.index') }}" class="h-12 px-6 bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:opacity-90 transition-all shadow-md flex items-center gap-2">
                        Explore Our Products
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </a>
                    <a href="{{ route('services.index') }}" class="h-12 px-6 bg-surface-container-lowest text-primary border border-primary rounded-lg font-label-md text-label-md hover:bg-surface-container-low transition-all flex items-center justify-center">
                        View Our Services
                    </a>
                </div>
                
                <!-- Bottom Features -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 border-t border-outline-variant/60">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[24px]">verified_user</span>
                        <div>
                            <p class="font-label-md text-label-sm text-on-surface">Premium Quality</p>
                            <p class="text-[11px] text-on-surface-variant">Clean, reliable & secure code</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[24px]">sync</span>
                        <div>
                            <p class="font-label-md text-label-sm text-on-surface">Regular Updates</p>
                            <p class="text-[11px] text-on-surface-variant">Continuous improvement</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[24px]">support_agent</span>
                        <div>
                            <p class="font-label-md text-label-sm text-on-surface">Expert Support</p>
                            <p class="text-[11px] text-on-surface-variant">We're here to help</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column Laptop Mockup -->
            <div class="lg:col-span-6 relative group perspective-1000">
                <div class="relative z-10 transition-transform duration-700 hover:scale-[1.02]">
                    <img alt="SaaSNinja Dashboard Mockup" class="w-full h-auto rounded-lg shadow-2xl border border-outline-variant/30" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCMrWQPzGrUbt_76f3-Ees5D4-yj6bQu_OfvU6aLeMmY2n-wkkubVFo6Ez7pYfP_Jy_KlmLV2MbKqC-KjjQ0pb5cI0YADNuA-jeD_G0FLn2ZMI5jGZE1INXQPDFHQ3kbEetcFPxMSDLMJwIyQSCxSUQ3Wjl3gdA5_rF-WyxX0Bio49N68icwuYnB6wi1lWlYjiNvc1h07I5UpWyRJw57fVrQXm-zHN8IQz_UgtII53QgdmH9crNunUd7A"/>
                </div>
                <!-- Backdrop blur effect -->
                <div class="absolute -inset-4 bg-primary/5 blur-3xl rounded-[2rem] -z-10 opacity-50"></div>
            </div>

        </div>
    </header>

    <!-- Trust Stats Banner -->
    <section class="py-10 bg-surface-container-low/40 border-y border-outline-variant/60">
        <div class="max-w-7xl mx-auto px-margin-page">
            <p class="text-center font-label-sm text-label-sm text-on-surface-variant/80 uppercase tracking-widest mb-6">Trusted by businesses and organizations worldwide</p>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6 justify-center items-center text-center">
                <div class="flex flex-col items-center">
                    <span class="material-symbols-outlined text-primary text-[32px] mb-2">groups</span>
                    <span class="font-outfit font-extrabold text-headline-md text-on-surface">10K+</span>
                    <span class="text-body-sm text-on-surface-variant font-medium">Happy Customers</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="material-symbols-outlined text-primary text-[32px] mb-2">package_2</span>
                    <span class="font-outfit font-extrabold text-headline-md text-on-surface">25+</span>
                    <span class="text-body-sm text-on-surface-variant font-medium">Products Delivered</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="material-symbols-outlined text-primary text-[32px] mb-2">download</span>
                    <span class="font-outfit font-extrabold text-headline-md text-on-surface">50K+</span>
                    <span class="text-body-sm text-on-surface-variant font-medium">Installations</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="material-symbols-outlined text-primary text-[32px] mb-2">verified</span>
                    <span class="font-outfit font-extrabold text-headline-md text-on-surface">99.9%</span>
                    <span class="text-body-sm text-on-surface-variant font-medium">System Reliability</span>
                </div>
                <div class="flex flex-col items-center col-span-2 md:col-span-1">
                    <span class="material-symbols-outlined text-primary text-[32px] mb-2">headphones</span>
                    <span class="font-outfit font-extrabold text-headline-md text-on-surface">24/7</span>
                    <span class="text-body-sm text-on-surface-variant font-medium">Expert Support</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Products Showcase -->
    <section class="py-16 bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-margin-page">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-16 gap-4">
                <div>
                    <h2 class="font-outfit font-extrabold text-headline-lg text-on-surface mb-2">Our Products</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Powerful, reliable and scalable applications built for real-world business needs.</p>
                </div>
                <a href="{{ route('products.index') }}" class="font-label-md text-label-md text-primary hover:underline flex items-center gap-2">
                    View All Products
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <!-- Product Grid (Dynamic from Database) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <a href="{{ route('products.show', $product->slug) }}" class="group bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden transition-all duration-300 shadow-sm hover:shadow-md hover:scale-[1.01] flex flex-col justify-between relative cursor-pointer no-underline text-inherit">
                        <div>
                            <!-- Product Image -->
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
                            
                            <!-- Card Body -->
                            <div class="p-6 space-y-3">
                                <div class="flex items-center justify-between gap-4">
                                    <h3 class="font-outfit font-bold text-base text-slate-900 dark:text-white leading-snug group-hover:text-primary transition-colors">
                                        {{ $product->name }}
                                    </h3>
                                    <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-md text-[10px] font-bold text-slate-500 whitespace-nowrap">v{{ $product->version }}</span>
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed line-clamp-3">
                                    {{ $product->short_description }}
                                </p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
    </section>

    <!-- Why SaaSNinja Benefits Section -->
    <section class="py-16 bg-surface-container-low/20 border-y border-outline-variant/60">
        <div class="max-w-7xl mx-auto px-margin-page">
            <div class="text-center max-w-xl mx-auto mb-16 space-y-2">
                <h2 class="font-outfit font-extrabold text-headline-lg text-on-surface">Why Developers Choose SaaSNinja</h2>
                <p class="font-body-md text-body-md text-on-surface-variant">We construct products targeting peak standards of code performance, modularity, and support.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Quality -->
                <div class="p-6 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm space-y-4">
                    <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">verified_user</span>
                    </div>
                    <h3 class="font-outfit font-bold text-body-lg text-on-surface">Premium Code Quality</h3>
                    <p class="text-body-sm text-on-surface-variant leading-relaxed">Clean architecture, fully documented hooks, and strict adherence to Laravel best practices.</p>
                </div>
                <!-- Updates -->
                <div class="p-6 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm space-y-4">
                    <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">update</span>
                    </div>
                    <h3 class="font-outfit font-bold text-body-lg text-on-surface">Regular Lifecycle Updates</h3>
                    <p class="text-body-sm text-on-surface-variant leading-relaxed">Constant updates reflecting framework changes, security updates, and feature upgrades.</p>
                </div>
                <!-- Support -->
                <div class="p-6 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm space-y-4">
                    <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">support_agent</span>
                    </div>
                    <h3 class="font-outfit font-bold text-body-lg text-on-surface">Professional Helpdesk</h3>
                    <p class="text-body-sm text-on-surface-variant leading-relaxed">Dedicated support ticket panel linked to Envato APIs for premium buyer attention.</p>
                </div>
                <!-- Customization -->
                <div class="p-6 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm space-y-4">
                    <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">construction</span>
                    </div>
                    <h3 class="font-outfit font-bold text-body-lg text-on-surface">Customization Services</h3>
                    <p class="text-body-sm text-on-surface-variant leading-relaxed">Full custom configuration, installation, server setups, and migration assistance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Blog CMS Preview -->
    <section class="py-16 bg-surface-container-lowest">
        <div class="max-w-7xl mx-auto px-margin-page">
            <div class="flex items-end justify-between mb-16">
                <div>
                    <h2 class="font-outfit font-extrabold text-headline-lg text-on-surface mb-2">Latest from Our Blog</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant">Updates, guides, and tutorials from the SaaSNinja team.</p>
                </div>
                <a href="{{ route('blog.index') }}" class="font-label-md text-label-md text-primary hover:underline flex items-center gap-2">
                    Read all posts
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($recentPosts as $post)
                    <article class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden shadow-sm flex flex-col justify-between">
                        <div class="h-44 bg-surface-container-low overflow-hidden">
                            @if ($post->featured_image)
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-350">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-secondary-container text-on-secondary-fixed-variant">
                                    <span class="material-symbols-outlined text-[48px]">article</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-5 flex-grow flex flex-col justify-between space-y-6">
                            <div>
                                <span class="font-label-sm text-[10px] text-primary uppercase tracking-widest font-bold">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                                </span>
                                <h3 class="font-outfit font-extrabold text-body-lg text-on-surface mt-1 line-clamp-2 hover:text-primary">
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p class="text-body-sm text-on-surface-variant line-clamp-3 leading-relaxed mt-4">
                                    {{ $post->summary }}
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-2">
                                    Read Article
                                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 text-center text-on-surface-variant py-6">
                        No blog posts published yet.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
