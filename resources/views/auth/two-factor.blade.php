@extends('layouts.public')

@section('title', 'Two-Factor Authentication - SaaSNinja')

@section('content')
<div class="py-16 bg-slate-50 dark:bg-slate-950 min-h-[calc(100vh-8rem)] flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-4">
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-xl space-y-6">
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto">
                    <span class="material-symbols-outlined text-[28px]">verified_user</span>
                </div>
                <h1 class="font-outfit font-extrabold text-2xl text-slate-900 dark:text-white tracking-tight">Two-Factor Authentication</h1>
                <p class="text-xs text-slate-500 max-w-xs mx-auto">
                    We've sent a 6-digit security verification code to <strong class="text-slate-800 dark:text-slate-200">{{ auth()->user()->email }}</strong>.
                </p>
            </div>

            @if (session('status'))
                <div class="p-3 bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 text-xs rounded-xl font-medium text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="code" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 text-center">Enter 6-Digit Code</label>
                    <input id="code" type="text" name="code" required autofocus maxlength="6" placeholder="000000"
                           class="block w-full text-center tracking-[12px] font-mono text-2xl py-3 px-4 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-950 text-slate-900 dark:text-white focus:ring-primary focus:border-primary">
                    @error('code')
                        <span class="text-xs text-red-500 mt-1.5 block text-center font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3 bg-primary text-white text-sm font-bold rounded-xl hover:opacity-90 transition-all shadow-md active:scale-95">
                    Verify & Continue
                </button>
            </form>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center text-xs">
                <form method="POST" action="{{ route('2fa.resend') }}">
                    @csrf
                    <button type="submit" class="text-primary hover:underline font-semibold">Resend Code</button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-red-500 font-medium">Cancel / Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
