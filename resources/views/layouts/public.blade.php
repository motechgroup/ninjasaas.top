<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', \App\Models\Setting::get('seo_title', 'SaaSNinja - Premium Software & Envato Customer Portal'))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('seo_description', 'SaaSNinja develops premium applications sold exclusively through Envato Market. Access documentation, get customer support, and request customization.'))">
    <meta name="keywords" content="@yield('meta_keywords', \App\Models\Setting::get('seo_keywords', 'laravel, saas, envato, support'))">
    <link rel="canonical" href="@yield('canonical_url', request()->url())">
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon', '/favicon.ico') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="@yield('title', \App\Models\Setting::get('seo_title', 'SaaSNinja'))">
    <meta property="og:description" content="@yield('meta_description', \App\Models\Setting::get('seo_description', 'SaaSNinja develops premium applications.'))">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.png'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ request()->url() }}">
    <meta property="twitter:title" content="@yield('title', \App\Models\Setting::get('seo_title', 'SaaSNinja'))">
    <meta property="twitter:description" content="@yield('meta_description', \App\Models\Setting::get('seo_description', 'SaaSNinja develops premium applications.'))">
    <meta property="twitter:image" content="@yield('og_image', asset('images/og-default.png'))">

    <!-- Structured Data -->
    @yield('schema')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Dynamic Theme Overrides -->
    <style>
        :root {
            --color-primary: {{ \App\Models\Setting::get('theme_primary_color', '#004ac6') }};
            --color-secondary: {{ \App\Models\Setting::get('theme_secondary_color', '#515f74') }};
            --color-background: {{ \App\Models\Setting::get('theme_background_color', '#f7f9fb') }};
            --color-surface-container: {{ \App\Models\Setting::get('theme_surface_color', '#eceef0') }};
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-background text-on-surface selection:bg-primary-fixed selection:text-on-primary-fixed">

    <!-- TopNavBar (Shared Component) -->
    <header class="w-full sticky top-0 z-50 bg-surface-container-lowest border-b border-outline-variant shadow-sm transition-all duration-200 ease-in-out">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between h-16">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    @if($logo = \App\Models\Setting::get('site_logo'))
                        <img src="{{ $logo }}" alt="{{ \App\Models\Setting::get('company_name', 'SaaSNinja') }}" style="height: {{ \App\Models\Setting::get('site_logo_height', '36') }}px; width: {{ \App\Models\Setting::get('site_logo_width', 'auto') }}; object-fit: contain;">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-white text-[24px] font-bold">code</span>
                        </div>
                        <span class="font-outfit font-extrabold text-2xl tracking-tight text-slate-900">{{ \App\Models\Setting::get('company_name', 'SaaSNinja') }}</span>
                    @endif
                </a>
                
                <nav class="hidden md:flex items-center gap-6 ml-8">
                    <a class="text-sm font-semibold transition-colors px-3 py-2 rounded-lg {{ request()->routeIs('home') ? 'text-primary font-bold border-b-2 border-primary' : 'text-slate-600 hover:text-primary' }}" href="{{ route('home') }}">Home</a>
                    <a class="text-sm font-semibold transition-colors px-3 py-2 rounded-lg {{ request()->routeIs('about') ? 'text-primary font-bold border-b-2 border-primary' : 'text-slate-600 hover:text-primary' }}" href="{{ route('about') }}">About</a>
                    <a class="text-sm font-semibold transition-colors px-3 py-2 rounded-lg {{ request()->routeIs('products.*') ? 'text-primary font-bold border-b-2 border-primary' : 'text-slate-600 hover:text-primary' }}" href="{{ route('products.index') }}">Products</a>
                    <a class="text-sm font-semibold transition-colors px-3 py-2 rounded-lg {{ request()->routeIs('services.*') ? 'text-primary font-bold border-b-2 border-primary' : 'text-slate-600 hover:text-primary' }}" href="{{ route('services.index') }}">Services</a>
                    <a class="text-sm font-semibold transition-colors px-3 py-2 rounded-lg {{ request()->routeIs('blog.*') ? 'text-primary font-bold border-b-2 border-primary' : 'text-slate-600 hover:text-primary' }}" href="{{ route('blog.index') }}">Blog</a>
                </nav>
            </div>

            <div class="flex items-center gap-6">
                <!-- Global Search Form -->
                <form action="{{ route('search') }}" method="GET" class="relative flex items-center">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search SaaSNinja..." class="w-36 sm:w-48 lg:w-60 px-3 py-1.5 pr-8 text-xs rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:outline-none focus:border-primary/50 text-slate-800 dark:text-slate-200 transition-all duration-300">
                    <button type="submit" class="absolute right-2.5 text-slate-450 hover:text-primary transition-colors flex items-center">
                        <span class="material-symbols-outlined text-[16px]">search</span>
                    </button>
                </form>

                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors px-2">Portal Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-slate-400 hover:text-red-655 font-semibold">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-primary transition-colors px-2">Log In</a>
                    <a href="{{ route('register') }}" class="text-sm font-bold text-primary hover:underline transition-colors px-2">Register</a>
                @endauth
                
                <a href="{{ route('contact') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold hover:opacity-90 transition-all active:scale-95 shadow-sm">
                    Get Support
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer (Shared Component) -->
    <footer class="w-full py-16 bg-slate-50 dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 text-slate-650 dark:text-slate-450">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8 mb-12">
                <div class="col-span-2 space-y-3">
                    <a href="{{ route('home') }}" class="inline-block">
                        @if($footerLogo = \App\Models\Setting::get('site_footer_logo') ?: \App\Models\Setting::get('site_logo'))
                            <img src="{{ $footerLogo }}" alt="{{ \App\Models\Setting::get('company_name', 'SaaSNinja') }}" style="height: {{ \App\Models\Setting::get('site_footer_logo_height', '36') }}px; width: auto; object-fit: contain;">
                        @else
                            <span class="font-outfit font-bold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[24px]">terminal</span>
                                {{ \App\Models\Setting::get('company_name', 'SaaSNinja') }}
                            </span>
                        @endif
                    </a>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-xs leading-relaxed">
                        An ambitious, forward-looking software company built on clean architecture, security-first design, and long-term maintainability.
                    </p>
                </div>
                <div>
                    <h4 class="font-outfit font-bold text-sm text-slate-900 dark:text-white mb-4">Products</h4>
                    <ul class="space-y-2 text-xs sm:text-sm text-slate-500">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('products.index') }}">Product Catalog</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('services.index') }}">LexCore Engine</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('services.index') }}">SaaSNinja CRM</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-outfit font-bold text-sm text-slate-900 dark:text-white mb-4">Services</h4>
                    <ul class="space-y-2 text-xs sm:text-sm text-slate-500">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('services.index') }}">Server Installation</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('services.index') }}">Custom Development</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('services.index') }}">API Integrations</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-outfit font-bold text-sm text-slate-900 dark:text-white mb-4">Resources</h4>
                    <ul class="space-y-2 text-xs sm:text-sm text-slate-500">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('about') }}">About Us</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('blog.index') }}">Blog</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('contact') }}">Get Support</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-outfit font-bold text-sm text-slate-900 dark:text-white mb-4">Trust</h4>
                    <ul class="space-y-2 text-xs sm:text-sm text-slate-500">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('privacy') }}">Privacy Policy</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('terms') }}">Terms of Service</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('refunds') }}">Refund Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-center pt-6 border-t border-slate-200 dark:border-slate-800 gap-4 text-xs sm:text-sm text-slate-400">
                <p>{{ \App\Models\Setting::get('site_footer_copyright', '© ' . date('Y') . ' SaaSNinja Software. All rights reserved. Sold exclusively on Envato Market.') }}</p>
                <div class="flex flex-wrap gap-4">
                    <a class="hover:text-primary transition-colors underline" href="{{ route('privacy') }}">Privacy Policy</a>
                    <a class="hover:text-primary transition-colors underline" href="{{ route('terms') }}">Terms of Service</a>
                    <a class="hover:text-primary transition-colors underline" href="{{ route('refunds') }}">Refund Policy</a>
                    <a class="hover:text-primary transition-colors underline" href="#">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
