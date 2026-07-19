@extends('layouts.public')

@section('title', 'Payment Success - SaaSNinja')

@section('content')
<div class="py-16 bg-slate-50 dark:bg-slate-950 min-h-[calc(100vh-8rem)] flex items-center justify-center">
    <div class="max-w-2xl w-full mx-auto px-4">
        
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-xl text-center space-y-6">
            
            <!-- Success Icon -->
            <div class="w-16 h-16 bg-green-50 dark:bg-green-950/30 text-green-600 dark:text-green-400 rounded-full flex items-center justify-center mx-auto shadow-md">
                <span class="material-symbols-outlined text-[36px]">check_circle</span>
            </div>

            <!-- Headers -->
            <div>
                <h2 class="font-outfit font-extrabold text-3xl text-slate-950 dark:text-white tracking-tight">Thank you for your purchase!</h2>
                <p class="text-sm text-slate-500 mt-2">Your payment has been processed successfully. Your license key and download packages are ready.</p>
            </div>

            <!-- License Key Card -->
            <div class="bg-slate-50 dark:bg-slate-950 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 max-w-md mx-auto space-y-3">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block">Your License Key</span>
                <code class="block text-lg sm:text-xl font-mono bg-white dark:bg-slate-900 border border-slate-150 dark:border-slate-800 py-2 px-4 rounded-xl text-slate-900 dark:text-white select-all">{{ $license->license_key }}</code>
                <span class="text-[10px] text-slate-400 block">Click or double-tap to copy code.</span>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-center gap-4">
                @if($latestVersion && $latestVersion->download_url)
                    <a href="{{ $latestVersion->download_url }}" class="w-full sm:w-auto px-6 py-3 bg-green-600 hover:bg-green-500 text-white text-sm font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined">download</span>
                        Download Package
                    </a>
                @endif
                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-6 py-3 bg-indigo-650 hover:bg-indigo-500 text-white text-sm font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">dashboard</span>
                    Go to Portal Dashboard
                </a>
            </div>

        </div>

    </div>
</div>
@endsection
