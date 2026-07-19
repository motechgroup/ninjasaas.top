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

    public function test_product_creation_and_updating_with_channels()
    {
        $admin = User::first();
        $admin->assignRole('Super Admin');

        $channel = SalesChannel::first();
        $category = \App\Models\ProductCategory::first();

        $response = $this->actingAs($admin)->post('/admin/products', [
            'product_category_id' => $category->id,
            'name' => 'New Awesome SaaS Product',
            'slug' => 'new-awesome-saas-product',
            'short_description' => 'A short description.',
            'description' => 'A detailed description.',
            'version' => '1.0.0',
            'is_active' => true,
            'channels' => [
                $channel->id => [
                    'enabled' => '1',
                    'purchase_url' => 'https://codecanyon.net/item/new-awesome/112233',
                    'price' => '49.00',
                    'priority' => '5',
                    'external_product_id' => '112233',
                ]
            ]
        ]);

        $response->assertStatus(302);
        
        $product = Product::where('slug', 'new-awesome-saas-product')->first();
        $this->assertNotNull($product);
        
        $this->assertDatabaseHas('product_sales_channels', [
            'product_id' => $product->id,
            'sales_channel_id' => $channel->id,
            'price' => 49.00,
            'purchase_url' => 'https://codecanyon.net/item/new-awesome/112233',
        ]);

        $response = $this->actingAs($admin)->patch('/admin/products/' . $product->id, [
            'product_category_id' => $category->id,
            'name' => 'Updated SaaS Product',
            'slug' => 'new-awesome-saas-product',
            'short_description' => 'A short description.',
            'description' => 'A detailed description.',
            'version' => '1.0.1',
            'is_active' => true,
            'channels' => [
                $channel->id => [
                    'enabled' => '1',
                    'purchase_url' => 'https://codecanyon.net/item/new-awesome/112233',
                    'price' => '79.00',
                    'priority' => '10',
                    'external_product_id' => '112233',
                ]
            ]
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('product_sales_channels', [
            'product_id' => $product->id,
            'sales_channel_id' => $channel->id,
            'price' => 79.00,
        ]);
    }

    public function test_product_checkout_pages_and_process()
    {
        $user = User::factory()->create();
        $product = Product::first();

        $response = $this->actingAs($user)->get('/checkout/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);

        $response = $this->actingAs($user)->post('/checkout/' . $product->slug, [
            'card_name' => $user->name,
            'card_number' => '4111222233334444',
            'card_expiry' => '12/28',
            'card_cvc' => '123',
        ]);

        $response->assertStatus(302);
        
        $license = License::where('user_id', $user->id)->where('product_id', $product->id)->first();
        $this->assertNotNull($license);
        $this->assertTrue($license->is_active);

        $response = $this->actingAs($user)->get('/checkout/success/' . $license->id);
        $response->assertStatus(200);
        $response->assertSee($license->license_key);
    }

    public function test_product_package_zip_upload()
    {
        $admin = User::first();
        $admin->assignRole('Super Admin');

        $product = Product::first();
        
        \Illuminate\Support\Facades\Storage::fake('public');
        $file = \Illuminate\Http\Testing\File::create('new-package.zip', 100, 'application/zip');

        $response = $this->actingAs($admin)->patch('/admin/products/' . $product->id, [
            'product_category_id' => $product->product_category_id,
            'name' => $product->name,
            'slug' => $product->slug,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'version' => '1.5.0',
            'is_active' => true,
            'package' => $file,
        ]);

        $response->assertStatus(302);
        
        $this->assertDatabaseHas('product_versions', [
            'product_id' => $product->id,
            'version' => '1.5.0',
        ]);

        $version = $product->versions()->where('version', '1.5.0')->first();
        $this->assertNotNull($version);
        $this->assertNotNull($version->download_url);
        
        $localPath = public_path(str_replace('/uploads/', 'uploads/', $version->download_url));
        if (file_exists($localPath)) {
            unlink($localPath);
        }
    }
}
