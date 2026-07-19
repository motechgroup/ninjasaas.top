<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\SalesChannel;
use App\Models\LicenseProvider;
use App\Models\License;
use App\Models\ProductSalesChannel;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SalesChannelAndLicensingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Sales Channels
        $envatoChannel = SalesChannel::firstOrCreate(
            ['slug' => 'envato'],
            ['name' => 'Envato Market', 'is_active' => true]
        );

        $directChannel = SalesChannel::firstOrCreate(
            ['slug' => 'saasninja'],
            ['name' => 'SaaSNinja Direct', 'is_active' => true]
        );

        // 2. Create License Providers
        $envatoProvider = LicenseProvider::firstOrCreate(
            ['slug' => 'envato'],
            ['name' => 'Envato API Verification', 'is_active' => true]
        );

        $directProvider = LicenseProvider::firstOrCreate(
            ['slug' => 'saasninja'],
            ['name' => 'SaaSNinja Licensing System', 'is_active' => true]
        );

        // 3. Map Products
        $lexCore = Product::where('slug', 'lexcore-landing-engine')->first();
        $crm = Product::where('slug', 'saasninja-crm-portal')->first();

        if ($lexCore) {
            // LexCore on Envato
            ProductSalesChannel::firstOrCreate(
                [
                    'product_id' => $lexCore->id,
                    'sales_channel_id' => $envatoChannel->id,
                ],
                [
                    'purchase_url' => 'https://codecanyon.net/item/lexcore-multipurpose-landing-engine/12345678',
                    'status' => 'active',
                    'priority' => 10,
                    'price' => 59.00,
                    'external_product_id' => '12345678',
                ]
            );

            // LexCore on SaaSNinja Direct
            ProductSalesChannel::firstOrCreate(
                [
                    'product_id' => $lexCore->id,
                    'sales_channel_id' => $directChannel->id,
                ],
                [
                    'purchase_url' => null, // Lead to contact page / direct billing
                    'status' => 'active',
                    'priority' => 5,
                    'price' => 49.00,
                    'external_product_id' => null,
                ]
            );
        }

        if ($crm) {
            // CRM on Envato
            ProductSalesChannel::firstOrCreate(
                [
                    'product_id' => $crm->id,
                    'sales_channel_id' => $envatoChannel->id,
                ],
                [
                    'purchase_url' => 'https://codecanyon.net/item/saasninja-crm-customer-portal/87654321',
                    'status' => 'active',
                    'priority' => 10,
                    'price' => 99.00,
                    'external_product_id' => '87654321',
                ]
            );
        }

        // 4. Seed Direct Licenses
        $client = User::first();
        if ($client && $lexCore) {
            License::firstOrCreate(
                ['license_key' => 'SN-DEMO-TEST-KEYS-2026'],
                [
                    'user_id' => $client->id,
                    'product_id' => $lexCore->id,
                    'license_provider_id' => $directProvider->id,
                    'purchased_at' => Carbon::now()->subMonths(1),
                    'expires_at' => Carbon::now()->addMonths(11),
                    'support_expires_at' => Carbon::now()->addMonths(11),
                    'is_active' => true,
                ]
            );
        }
    }
}
