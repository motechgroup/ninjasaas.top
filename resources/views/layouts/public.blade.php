<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', \App\Models\Setting::get('seo_title', 'SaaSNinja - Premium Software & Envato Customer Portal'))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('seo_description', 'SaaSNinja develops premium applications sold exclusively through Envato Market. Access documentation, get customer support, and request customization.'))">
    <meta name="keywords" content="{{ \App\Models\Setting::get('seo_keywords', 'laravel, saas, envato, support') }}">
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon', '/favicon.ico') }}">

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
        <div class="max-w-7xl mx-auto px-margin-page flex items-center justify-between h-16">
            <div class="flex items-center gap-md">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    @if(\App\Models\Setting::get('site_logo'))
                        <img src="{{ \App\Models\Setting::get('site_logo') }}" alt="Logo" class="h-8 w-auto">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center shadow-md">
                            <span class="material-symbols-outlined text-white text-[24px] font-bold">code</span>
                        </div>
                    @endif
                    <span class="font-outfit font-extrabold text-2xl tracking-tight text-slate-900">{{ \App\Models\Setting::get('company_name', 'SaaSNinja') }}</span>
                </a>
                
                <nav class="hidden md:flex items-center gap-md ml-xl">
                    <a class="font-body-md text-body-md transition-colors px-xs py-base rounded-lg {{ request()->routeIs('home') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-secondary-container hover:text-primary' }}" href="{{ route('home') }}">Home</a>
                    <a class="font-body-md text-body-md transition-colors px-xs py-base rounded-lg {{ request()->routeIs('products.*') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-secondary-container hover:text-primary' }}" href="{{ route('products.index') }}">Products</a>
                    <a class="font-body-md text-body-md transition-colors px-xs py-base rounded-lg {{ request()->routeIs('services.*') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-secondary-container hover:text-primary' }}" href="{{ route('services.index') }}">Services</a>
                    <a class="font-body-md text-body-md transition-colors px-xs py-base rounded-lg {{ request()->routeIs('docs.*') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-secondary-container hover:text-primary' }}" href="{{ route('docs.index') }}">Help Guides</a>
                    <a class="font-body-md text-body-md transition-colors px-xs py-base rounded-lg {{ request()->routeIs('blog.*') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-secondary-container hover:text-primary' }}" href="{{ route('blog.index') }}">Tech Blog</a>
                    <a class="font-body-md text-body-md transition-colors px-xs py-base rounded-lg {{ request()->routeIs('contact') ? 'text-primary font-bold border-b-2 border-primary' : 'text-on-secondary-container hover:text-primary' }}" href="{{ route('contact') }}">Contact</a>
                </nav>
            </div>

            <div class="flex items-center gap-md">
                <!-- Search Button -->
                <button class="flex items-center text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[24px]">search</span>
                </button>

                @auth
                    <a href="{{ route('dashboard') }}" class="font-label-md text-label-md text-on-secondary-container hover:text-primary transition-colors px-xs">Portal Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-outline hover:text-error font-semibold">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="font-label-md text-label-md text-on-secondary-container hover:text-primary transition-colors px-xs">Log In</a>
                @endauth
                
                <a href="{{ route('portal.tickets') }}" class="bg-primary text-on-primary px-md py-2 rounded-lg font-label-md text-label-md hover:bg-surface-tint transition-all active:scale-95 shadow-sm">
                    Get Support
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer (Shared Component) -->
    <footer class="w-full py-xl bg-surface-container-low border-t border-outline-variant opacity-80 hover:opacity-100 transition-opacity">
        <div class="max-w-7xl mx-auto px-margin-page">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-gutter mb-xl">
                <div class="col-span-2">
                    <span class="font-headline-sm text-headline-sm font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[24px]">terminal</span>
                        SaaSNinja
                    </span>
                    <p class="mt-md font-body-sm text-body-sm text-on-surface-variant max-w-xs leading-relaxed">
                        Delivering world-class digital tools and premium Envato components for digital-first enterprises since 2018.
                    </p>
                </div>
                <div>
                    <h4 class="font-label-md text-label-md text-on-surface mb-md">Products</h4>
                    <ul class="space-y-xs font-body-sm text-body-sm text-on-surface-variant">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('products.index') }}">Product Catalog</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">LexCore Engine</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">SaaSNinja CRM</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-label-md text-label-md text-on-surface mb-md">Services</h4>
                    <ul class="space-y-xs font-body-sm text-body-sm text-on-surface-variant">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('services.index') }}">Server Installation</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('services.index') }}">Custom Development</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('docs.index') }}">API Integrations</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-label-md text-label-md text-on-surface mb-md">Resources</h4>
                    <ul class="space-y-xs font-body-sm text-body-sm text-on-surface-variant">
                        <li><a class="hover:text-primary transition-colors" href="{{ route('docs.index') }}">Help Guides</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('blog.index') }}">Tech Blog</a></li>
                        <li><a class="hover:text-primary transition-colors" href="{{ route('contact') }}">Contact Sales</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-label-md text-label-md text-on-surface mb-md">Trust</h4>
                    <ul class="space-y-xs font-body-sm text-body-sm text-on-surface-variant">
                        <li><a class="hover:text-primary transition-colors" href="#">Privacy Policy</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Terms of Service</a></li>
                        <li><a class="hover:text-primary transition-colors" href="#">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row justify-between items-center pt-md border-t border-outline-variant gap-sm">
                <p class="font-body-sm text-body-sm text-on-surface-variant">{{ \App\Models\Setting::get('site_footer_copyright', '© ' . date('Y') . ' SaaSNinja Software. All rights reserved. Sold exclusively on Envato Market.') }}</p>
                <div class="flex gap-md font-body-sm text-body-sm">
                    <a class="text-on-surface-variant hover:text-primary transition-colors underline" href="#">Privacy Policy</a>
                    <a class="text-on-surface-variant hover:text-primary transition-colors underline" href="#">Terms of Service</a>
                    <a class="text-on-surface-variant hover:text-primary transition-colors underline" href="#">Cookie Policy</a>
                    <a class="text-on-surface-variant hover:text-primary transition-colors underline" href="#">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
