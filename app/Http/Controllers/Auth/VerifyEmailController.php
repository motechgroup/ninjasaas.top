<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Verify email using 6-digit OTP code.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($user->two_factor_code === $request->otp && $user->two_factor_expires_at && $user->two_factor_expires_at->isFuture()) {
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            session(['2fa_passed' => true]);

            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1')
                ->with('success', 'Your email address has been verified successfully!');
        }

        return back()->withErrors(['otp' => 'Invalid or expired 6-digit verification code. Please check your email or click Resend Code.']);
    }

    /**
     * Mark the authenticated user's email address as verified (legacy signed link).
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        session(['2fa_passed' => true]);

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
