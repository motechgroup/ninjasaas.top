<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $mailHost = \App\Models\Setting::get('mail_host');
                if ($mailHost) {
                    $verifySsl = \App\Models\Setting::get('mail_verify_ssl', 'true') === 'true';
                    $config = [
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $mailHost,
                        'mail.mailers.smtp.port' => \App\Models\Setting::get('mail_port', '2525'),
                        'mail.mailers.smtp.username' => \App\Models\Setting::get('mail_username'),
                        'mail.mailers.smtp.password' => \App\Models\Setting::get('mail_password'),
                        'mail.mailers.smtp.encryption' => \App\Models\Setting::get('mail_encryption', 'tls'),
                        'mail.from.address' => \App\Models\Setting::get('mail_from_address', 'hello@saasninja.top'),
                        'mail.from.name' => \App\Models\Setting::get('company_name', 'SaaSNinja'),
                    ];

                    if (!$verifySsl) {
                        $config['mail.mailers.smtp.stream'] = [
                            'ssl' => [
                                'allow_self_signed' => true,
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                            ],
                        ];
                    }

                    config($config);
                }
            }
        } catch (\Exception $e) {
            // Silence exceptions during early bootstrap or migration phases
        }

        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function ($notifiable, $token) {
            $appName = \App\Models\Setting::get('company_name', 'SaaSNinja');
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $html = \App\Services\EmailBrandingService::renderHtmlEmail(
                title: "Reset Password Notification",
                greeting: "Hello {$notifiable->name},",
                paragraphs: [
                    "You are receiving this email because we received a password reset request for your {$appName} account.",
                    "Click the button below to choose a new password:"
                ],
                highlightBox: null,
                buttonUrl: $resetUrl,
                buttonText: "Reset Password",
                subtext: "This password reset link will expire in 60 minutes. If you did not request a password reset, no further action is required."
            );

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject("Reset Your {$appName} Account Password")
                ->view('emails.branded', ['htmlContent' => $html]);
        });
    }
}
