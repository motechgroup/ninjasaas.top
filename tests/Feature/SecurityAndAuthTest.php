<?php

namespace Tests\Feature;

use App\Models\EnvatoItem;
use App\Models\EnvatoPurchase;
use App\Models\License;
use App\Models\LicenseProvider;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductVersion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityAndAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_present_on_web_responses(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_api_license_verification_rate_limiting(): void
    {
        for ($i = 0; $i < 30; $i++) {
            $this->postJson('/api/license/verify', ['purchase_code' => 'invalid-code-test-12345']);
        }

        $response = $this->postJson('/api/license/verify', ['purchase_code' => 'invalid-code-test-12345']);
        $response->assertStatus(429);
    }

    public function test_unauthorized_user_cannot_download_product_package(): void
    {
        $category = ProductCategory::create(['name' => 'Web Apps', 'slug' => 'web-apps']);
        $product = Product::create([
            'product_category_id' => $category->id,
            'name' => 'Protected Script',
            'slug' => 'protected-script',
            'short_description' => 'A protected script',
            'description' => 'Full description of protected script',
            'version' => '1.0.0',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['2fa_passed' => true])
            ->get(route('portal.products.download', $product->slug));

        $response->assertStatus(403);
    }

    public function test_authorized_license_owner_can_download_product_package(): void
    {
        Storage::fake('local');
        $packagePath = 'products/sample_app.zip';
        Storage::disk('local')->put($packagePath, 'dummy content zip');

        $category = ProductCategory::create(['name' => 'Web Apps', 'slug' => 'web-apps']);
        $product = Product::create([
            'product_category_id' => $category->id,
            'name' => 'Protected Script',
            'slug' => 'protected-script',
            'short_description' => 'A protected script',
            'description' => 'Full description of protected script',
            'version' => '1.0.0',
            'is_active' => true,
        ]);

        ProductVersion::create([
            'product_id' => $product->id,
            'version' => '1.0.0',
            'download_url' => $packagePath,
            'release_date' => now(),
        ]);

        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $provider = LicenseProvider::create(['name' => 'Direct', 'slug' => 'saasninja']);
        License::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'license_provider_id' => $provider->id,
            'license_key' => 'SN-TEST-KEY-1234',
            'purchased_at' => now(),
            'expires_at' => now()->addYear(),
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['2fa_passed' => true])
            ->get(route('portal.products.download', $product->slug));

        $response->assertStatus(200);
    }

    public function test_non_google_user_email_login_triggers_2fa_flow(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'google_id' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('2fa.show'));

        $user->refresh();
        $this->assertNotNull($user->two_factor_code);
        $this->assertNotNull($user->two_factor_expires_at);
    }

    public function test_2fa_code_verification_grants_access_to_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'google_id' => null,
            'two_factor_code' => '654321',
            'two_factor_expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->actingAs($user)
            ->withSession(['2fa_passed' => false])
            ->post('/auth/2fa', ['code' => '654321']);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(session('2fa_passed'));
    }

    public function test_unverified_email_user_cannot_access_dashboard(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
            'google_id' => null,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['2fa_passed' => true])
            ->get('/dashboard');

        $response->assertRedirect(route('verification.notice'));
    }
}
