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
    }
}
