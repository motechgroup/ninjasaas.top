<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        if (\App\Models\Setting::get('enable_google_login', 'true') !== 'true') {
            return redirect()->route('login')->withErrors(['email' => 'Google Login is currently disabled by the administrator.']);
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function handleGoogleCallback()
    {
        if (\App\Models\Setting::get('enable_google_login', 'true') !== 'true') {
            return redirect()->route('login')->withErrors(['email' => 'Google Login is currently disabled by the administrator.']);
        }
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['email' => 'Unable to authenticate with Google. Please try again.']);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'email_verified_at' => $user->email_verified_at ?: now(),
            ]);
        } else {
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Google User',
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(24)),
            ]);

            if (method_exists($user, 'assignRole') && \Spatie\Permission\Models\Role::where('name', 'customer')->exists()) {
                $user->assignRole('customer');
            }
        }

        Auth::login($user, true);
        session(['2fa_passed' => true]);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Redirect the user to the Envato authentication page.
     */
    public function redirectToEnvato(\App\Services\EnvatoService $envatoService)
    {
        if (\App\Models\Setting::get('enable_envato_login', 'true') !== 'true') {
            return redirect()->route('login')->withErrors(['email' => 'Envato Login is currently disabled by the administrator.']);
        }

        $oauthUrl = $envatoService->getOAuthUrl();

        if ($oauthUrl === '#') {
            return redirect()->route('login')->withErrors(['email' => 'Envato Client ID is not configured in Admin Settings. Please enter your Client ID in Admin Settings -> Global Settings.']);
        }

        return redirect()->away($oauthUrl);
    }

    /**
     * Obtain the user information from Envato.
     */
    public function handleEnvatoCallback(Request $request, \App\Services\EnvatoService $envatoService)
    {
        if (\App\Models\Setting::get('enable_envato_login', 'true') !== 'true') {
            return redirect()->route('login')->withErrors(['email' => 'Envato Login is currently disabled by the administrator.']);
        }

        $code = $request->input('code');
        $accessToken = $request->input('access_token');

        if (!$code && !$accessToken) {
            return response()->make('
                <!DOCTYPE html><html><head><title>Authenticating with Envato...</title></head>
                <body style="font-family:sans-serif;text-align:center;padding-top:100px;background:#0f172a;color:#fff;">
                    <h2>Authenticating with Envato...</h2>
                    <p>Please wait while we complete your login.</p>
                    <script>
                        if (window.location.hash && window.location.hash.includes("access_token=")) {
                            const params = new URLSearchParams(window.location.hash.substring(1));
                            const token = params.get("access_token");
                            if (token) {
                                window.location.href = "' . route('auth.envato.callback') . '?access_token=" + encodeURIComponent(token);
                            } else {
                                window.location.href = "' . route('login') . '";
                            }
                        } else {
                            window.location.href = "' . route('login') . '";
                        }
                    </script>
                </body></html>
            ');
        }

        if ($accessToken) {
            $profileRes = $envatoService->getUserProfile($accessToken);
            if (!$profileRes['success']) {
                return redirect()->route('login')->withErrors(['email' => $profileRes['error']]);
            }

            $username = $profileRes['username'];
            $email = $profileRes['email'] ?: ($username . '@envato.user');
            $avatar = $profileRes['avatar'];
        } elseif ($code) {
            $tokenRes = $envatoService->exchangeCodeForToken($code);
            if (!$tokenRes['success']) {
                return redirect()->route('login')->withErrors(['email' => $tokenRes['error']]);
            }

            $profileRes = $envatoService->getUserProfile($tokenRes['access_token']);
            if (!$profileRes['success']) {
                return redirect()->route('login')->withErrors(['email' => $profileRes['error']]);
            }

            $username = $profileRes['username'];
            $email = $profileRes['email'] ?: ($username . '@envato.user');
            $avatar = $profileRes['avatar'];
        } else {
            return redirect()->route('login')->withErrors(['email' => 'Envato authentication canceled or missing authorization code.']);
        }

        $user = User::where('envato_username', $username)
            ->orWhere('email', $email)
            ->first();

        if ($user) {
            $user->update([
                'envato_username' => $username,
                'envato_avatar' => $avatar ?: $user->envato_avatar,
                'email_verified_at' => $user->email_verified_at ?: now(),
            ]);
        } else {
            $user = User::create([
                'name' => $username,
                'email' => $email,
                'envato_username' => $username,
                'envato_avatar' => $avatar,
                'email_verified_at' => now(),
                'password' => bcrypt(Str::random(24)),
            ]);

            if (method_exists($user, 'assignRole') && \Spatie\Permission\Models\Role::where('name', 'customer')->exists()) {
                $user->assignRole('customer');
            }
        }

        Auth::login($user, true);
        session(['2fa_passed' => true]);

        return redirect()->intended(route('dashboard'));
    }
}
