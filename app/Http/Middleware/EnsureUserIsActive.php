<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            if ($user->isBlocked()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $reason = $user->status_reason ?: 'Your account has been permanently blocked by an administrator.';
                return redirect()->route('login')->withErrors(['email' => $reason]);
            }

            if ($user->isSuspended()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                $until = $user->suspended_until ? ' until ' . $user->suspended_until->format('M d, Y H:i T') : '';
                $reason = 'Your account has been suspended' . $until . '. Reason: ' . ($user->status_reason ?: 'Violation of terms of service.');
                return redirect()->route('login')->withErrors(['email' => $reason]);
            }
        }

        return $next($request);
    }
}
