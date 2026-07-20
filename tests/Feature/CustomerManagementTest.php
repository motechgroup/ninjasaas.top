<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class CustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Super Admin']);
        Role::firstOrCreate(['name' => 'Customer']);

        $this->admin = User::factory()->create([
            'email' => 'admin@saasninja.top',
            'status' => 'active',
        ]);
        $this->admin->assignRole('Super Admin');

        $this->customer = User::factory()->create([
            'name' => 'John Customer',
            'email' => 'john@example.com',
            'status' => 'active',
        ]);
        $this->customer->assignRole('Customer');
    }

    public function test_admin_can_view_customers_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/customers');
        $response->assertStatus(200);
        $response->assertSee('John Customer');
    }

    public function test_admin_can_search_and_filter_customers(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/customers?search=John');
        $response->assertStatus(200);
        $response->assertSee('john@example.com');
    }

    public function test_admin_can_suspend_customer_and_middleware_blocks_access(): void
    {
        $response = $this->actingAs($this->admin)->patch("/admin/customers/{$this->customer->id}/status", [
            'status' => 'suspended',
            'status_reason' => 'Spam behavior',
            'suspended_days' => 7,
        ]);
        $response->assertRedirect();
        $this->assertEquals('suspended', $this->customer->fresh()->status);

        // Attempting to access site as suspended user logs them out
        $userResponse = $this->actingAs($this->customer->fresh())->withSession(['2fa_passed' => true])->get('/dashboard');
        $userResponse->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_admin_can_block_customer_and_middleware_blocks_access(): void
    {
        $response = $this->actingAs($this->admin)->patch("/admin/customers/{$this->customer->id}/status", [
            'status' => 'blocked',
            'status_reason' => 'Fraudulent activity',
        ]);
        $response->assertRedirect();
        $this->assertEquals('blocked', $this->customer->fresh()->status);

        $userResponse = $this->actingAs($this->customer->fresh())->withSession(['2fa_passed' => true])->get('/dashboard');
        $userResponse->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_admin_can_reactivate_suspended_customer(): void
    {
        $this->customer->update(['status' => 'suspended']);

        $response = $this->actingAs($this->admin)->patch("/admin/customers/{$this->customer->id}/status", [
            'status' => 'active',
        ]);
        $response->assertRedirect();
        $this->assertEquals('active', $this->customer->fresh()->status);
    }

    public function test_admin_can_update_customer_profile(): void
    {
        $response = $this->actingAs($this->admin)->patch("/admin/customers/{$this->customer->id}/profile", [
            'name' => 'John Updated',
            'email' => 'john.updated@example.com',
            'envato_username' => 'john_buyer',
            'role' => 'Customer',
        ]);
        $response->assertRedirect();
        $this->assertEquals('John Updated', $this->customer->fresh()->name);
        $this->assertEquals('john_buyer', $this->customer->fresh()->envato_username);
    }

    public function test_admin_can_delete_customer(): void
    {
        $response = $this->actingAs($this->admin)->delete("/admin/customers/{$this->customer->id}");
        $response->assertRedirect(route('admin.customers.index'));
        $this->assertDatabaseMissing('users', ['id' => $this->customer->id]);
    }

    public function test_admin_cannot_self_suspend_or_self_delete(): void
    {
        $statusResponse = $this->actingAs($this->admin)->patch("/admin/customers/{$this->admin->id}/status", [
            'status' => 'suspended',
        ]);
        $statusResponse->assertSessionHasErrors();

        $deleteResponse = $this->actingAs($this->admin)->delete("/admin/customers/{$this->admin->id}");
        $deleteResponse->assertSessionHasErrors();
    }

    public function test_admin_can_impersonate_and_stop_impersonating_customer(): void
    {
        $response = $this->actingAs($this->admin)->post("/admin/customers/{$this->customer->id}/impersonate");
        $response->assertRedirect(route('dashboard'));
        $this->assertEquals($this->customer->id, auth()->id());
        $this->assertEquals($this->admin->id, session('impersonator_id'));

        $stopResponse = $this->get('/impersonate/stop');
        $stopResponse->assertRedirect(route('admin.customers.index'));
        $this->assertEquals($this->admin->id, auth()->id());
        $this->assertNull(session('impersonator_id'));
    }
}
