<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\LicenseProvider;
use App\Models\License;
use App\Models\SalesChannel;
use App\Models\ProductSalesChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class MultiChannelLicensingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
        $this->artisan('db:seed', ['--class' => 'SettingSeeder']);
        $this->artisan('db:seed', ['--class' => 'ProductSeeder']);
        $this->artisan('db:seed', ['--class' => 'SalesChannelAndLicensingSeeder']);
    }

    public function test_envato_license_verification_api()
    {
        $response = $this->postJson('/api/license/verify', [
            'purchase_code' => '87654321-abcd-efgh-ijkl-1234567890ab',
            'product_id' => '12345678'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'valid',
            'license_status',
            'license_type',
            'buyer',
            'purchase_date',
            'support_expiry'
        ]);
    }

    public function test_saasninja_direct_license_verification_api()
    {
        $product = Product::first();
        $provider = LicenseProvider::where('slug', 'saasninja')->first();
        $user = User::factory()->create();

        $licenseKey = 'SN-UNIT-TEST-KEYS-2026';
        License::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'license_provider_id' => $provider->id,
            'license_key' => $licenseKey,
            'purchased_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addYear(),
            'support_expires_at' => Carbon::now()->addYear(),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/license/verify', [
            'purchase_code' => $licenseKey,
            'product_id' => $product->slug,
            'domain' => 'test-domain.com'
        ]);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'valid' => true,
            'license_status' => 'active',
            'license_type' => 'Direct License'
        ]);

        $this->assertDatabaseHas('license_activations', [
            'domain' => 'test-domain.com'
        ]);
    }

    public function test_inactive_direct_license_fails_verification()
    {
        $product = Product::first();
        $provider = LicenseProvider::where('slug', 'saasninja')->first();
        $user = User::factory()->create();

        $licenseKey = 'SN-INACTIVE-KEYS-2026';
        License::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'license_provider_id' => $provider->id,
            'license_key' => $licenseKey,
            'purchased_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addYear(),
            'support_expires_at' => Carbon::now()->addYear(),
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/license/verify', [
            'purchase_code' => $licenseKey,
            'product_id' => $product->slug,
            'domain' => 'test-domain.com'
        ]);

        $response->assertStatus(400);
        $response->assertJsonFragment([
            'valid' => false,
            'error' => 'This license key has been deactivated.'
        ]);
    }
}
