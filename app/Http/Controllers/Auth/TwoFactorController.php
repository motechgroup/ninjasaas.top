<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    /**
     * Show the 2FA verification prompt view.
     */
    public function show()
    {
        if (session('2fa_passed')) {
            return redirect()->route('dashboard');
        }

        return view('auth.two-factor');
    }

    /**
     * Verify the submitted 2FA code.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if (
            $user->two_factor_code &&
            $user->two_factor_code === trim($request->input('code')) &&
            $user->two_factor_expires_at &&
            $user->two_factor_expires_at->isFuture()
        ) {
            $user->update([
                'two_factor_code' => null,
                'two_factor_expires_at' => null,
            ]);

            session(['2fa_passed' => true]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['code' => 'The provided security code is invalid or has expired.']);
    }

    /**
     * Resend a fresh 2FA code to user's email.
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        $code = sprintf('%06d', mt_rand(100000, 999999));
        $user->update([
            'two_factor_code' => $code,
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new TwoFactorCodeMail($code));
        } catch (\Exception $e) {
            // Mail transport error handling gracefully
        }

        return back()->with('status', 'A fresh security verification code has been sent to your email.');
    }
}
