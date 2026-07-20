<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? trim(strip_tags($title)) . ' - ' : '' }}{{ \App\Models\Setting::get('site_title', config('app.name', 'SaaSNinja')) }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dynamic Theme Overrides -->
    <style>
        :root {
            --color-primary: {{ \App\Models\Setting::get('theme_primary_color', '#004ac6') }};
            --color-secondary: {{ \App\Models\Setting::get('theme_secondary_color', '#515f74') }};
            --color-background: {{ \App\Models\Setting::get('theme_background_color', '#f7f9fb') }};
            --color-surface-container: {{ \App\Models\Setting::get('theme_surface_color', '#eceef0') }};
        }
    </style>
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon', '/favicon.ico') }}">
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100 min-h-screen transition-colors duration-300">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 dark:bg-slate-950 relative overflow-hidden">
        
        <!-- Ambient Background Gradients -->
        <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-violet-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        
        <div class="z-10 flex flex-col items-center">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-2 group mb-8">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-violet-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span class="font-outfit font-extrabold text-2xl tracking-tight text-slate-950 dark:text-white">
                    SaaS<span class="text-indigo-600 dark:text-indigo-400">Ninja</span>
                </span>
            </a>

            <!-- Glassmorphism Card Wrapper -->
            <div class="w-full sm:max-w-md px-8 py-10 bg-white/70 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/50 dark:border-slate-800/50 shadow-2xl rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
