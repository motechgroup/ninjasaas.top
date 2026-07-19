<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('company_name', 'SaaSNinja', 'string');
        Setting::set('support_email', 'support@saasninja.top', 'string');
        Setting::set('allow_registrations', 'true', 'boolean');
        Setting::set('envato_sandbox_mode', 'true', 'boolean');
        Setting::set('stripe_public_key', env('STRIPE_PUBLIC_KEY', 'stripe_mock_public_key_fallback'), 'string');
        Setting::set('stripe_secret_key', env('STRIPE_SECRET_KEY', 'stripe_mock_secret_key_fallback'), 'string');
    }
}
