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
    }
}
