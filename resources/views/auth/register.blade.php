<x-guest-layout>
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
        <div x-data="{ showEnvatoConfirm: false }">
            <button type="button" @click="showEnvatoConfirm = true" style="background-color: #82B440; color: #ffffff !important;" class="w-full flex items-center justify-center gap-3 px-4 py-2.5 rounded-xl text-sm font-bold transition-all shadow-md hover:opacity-90">
                <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                <span>Sign up with Envato</span>
            </button>

            <!-- Envato Confirmation Dialog -->
            <div x-show="showEnvatoConfirm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
                <div @click.away="showEnvatoConfirm = false" class="bg-white dark:bg-slate-900 rounded-2xl max-w-sm w-full p-6 border border-slate-200/80 dark:border-slate-800 shadow-2xl text-center space-y-4">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto shadow-lg" style="background-color: #82B440; color: #ffffff;">
                        <span class="material-symbols-outlined text-3xl">shopping_bag</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white font-outfit">Authenticate with Envato</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">You will be securely redirected to Envato Market to confirm your account signup. Email verification is automatically bypassed.</p>
                    </div>
                    <div class="flex flex-col gap-2 pt-2">
                        <a href="{{ route('auth.envato') }}" style="background-color: #82B440; color: #ffffff !important;" class="w-full py-2.5 rounded-xl text-sm font-bold shadow-md hover:opacity-90 transition-opacity">
                            Proceed to Envato Signup
                        </a>
                        <button type="button" @click="showEnvatoConfirm = false" class="w-full py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-semibold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
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

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
