<x-guest-layout>
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center mb-3">
            @if($logo = \App\Models\Setting::get('site_logo'))
                <img src="{{ $logo }}" alt="{{ \App\Models\Setting::get('company_name', 'SaaSNinja') }}" style="height: {{ \App\Models\Setting::get('site_logo_height', '40') }}px; width: {{ \App\Models\Setting::get('site_logo_width', 'auto') }}; object-fit: contain;">
            @else
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-[24px]">person_add</span>
                </div>
            @endif
        </div>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white font-outfit">Create Your Account</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Join {{ \App\Models\Setting::get('company_name', 'SaaSNinja') }} to get started</p>
    </div>
    @if (\App\Models\Setting::get('enable_google_login', 'true') === 'true' || \App\Models\Setting::get('enable_envato_login', 'true') === 'true')
    <div class="mb-6 space-y-3">
        @if (\App\Models\Setting::get('enable_google_login', 'true') === 'true')
        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-700 dark:text-slate-200 text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all shadow-sm">
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#EA4335" d="M12 5c1.6 0 3 .6 4.1 1.6l3.1-3.1C17.3 1.7 14.8 1 12 1 7.5 1 3.7 3.6 1.9 7.3l3.7 2.9C6.5 7.4 9 5 12 5z"/>
                <path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.5h6.5c-.3 1.5-1.1 2.8-2.4 3.7l3.7 2.9c2.2-2 3.7-5 3.7-8.8z"/>
                <path fill="#FBBC05" d="M5.6 14.8c-.2-.7-.4-1.5-.4-2.3s.2-1.6.4-2.3L1.9 7.3C.7 9.7 0 12.3 0 15s.7 5.3 1.9 7.7l3.7-2.9z"/>
                <path fill="#34A853" d="M12 23c3.2 0 6-1.1 8-3l-3.7-2.9c-1.1.7-2.5 1.2-4.3 1.2-3 0-5.5-2.4-6.4-5.2L1.9 16C3.7 19.7 7.5 23 12 23z"/>
            </svg>
            <span>Sign up with Google</span>
        </a>
        @endif

        @if (\App\Models\Setting::get('enable_envato_login', 'true') === 'true')
        <a href="{{ route('auth.envato') }}" style="background-color: #82B440; color: #ffffff !important;" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md hover:opacity-90">
            <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
            <span>Sign up with Envato</span>
        </a>
        @endif

        <div class="relative flex items-center justify-center pt-2">
            <div class="border-t border-slate-200 dark:border-slate-800 w-full"></div>
            <span class="bg-white dark:bg-slate-900 px-3 text-xs text-slate-400 font-semibold uppercase absolute">or email</span>
        </div>
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
            <a class="underline text-xs text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered? Log in') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Create Account') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
