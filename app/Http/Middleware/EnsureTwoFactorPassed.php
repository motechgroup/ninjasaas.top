<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorPassed
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            // Google login users bypass 2FA prompt
            if ($user->google_id) {
                session(['2fa_passed' => true]);
                return $next($request);
            }

            if (!session('2fa_passed')) {
                if ($request->routeIs('2fa.*') || $request->routeIs('logout')) {
                    return $next($request);
                }

                return redirect()->route('2fa.show');
            }
        }

        return $next($request);
    }
}
