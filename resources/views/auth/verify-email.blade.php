<x-guest-layout>
    <div class="text-center space-y-4">
        <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 border border-indigo-200 dark:border-indigo-800 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto shadow-md">
            <span class="material-symbols-outlined text-3xl">mark_email_read</span>
        </div>

        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white font-outfit">Verify Your Email Address</h2>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                We sent a 6-digit verification code to<br>
                <span class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ Auth::user()->email }}</span>
            </p>
        </div>

        @if (session('status') == 'verification-code-sent' || session('status') == 'verification-link-sent')
            <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/60 rounded-xl text-emerald-700 dark:text-emerald-400 text-xs font-semibold">
                ✓ A new 6-digit verification code has been dispatched to your email inbox.
            </div>
        @endif

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('verification.verify.otp') }}" class="space-y-4 pt-2">
            @csrf

            <div>
                <label for="otp" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    Enter 6-Digit OTP Code
                </label>
                <input type="text" id="otp" name="otp" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required autofocus
                       placeholder="123456"
                       class="w-full text-center tracking-[0.4em] font-mono text-2xl font-bold rounded-xl border-slate-200 dark:border-slate-800 dark:bg-slate-950 text-slate-900 dark:text-white py-3 px-4 focus:ring-primary focus:border-primary shadow-sm">
                <x-input-error :messages="$errors->get('otp')" class="mt-2 text-center text-xs" />
            </div>

            <button type="submit" class="w-full bg-primary hover:bg-primary-hover text-white text-sm font-bold py-3 px-4 rounded-xl transition-all shadow-md active:scale-98">
                Verify Email Address
            </button>
        </form>

        <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-800 text-xs">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="font-semibold text-primary hover:underline">
                    Resend Code
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 font-semibold">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
