<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FranchiseActiveInactiveTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $activeFranchise;
    protected User $inactiveFranchise;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com',
            'status' => 'active',
        ]);

        $this->activeFranchise = User::factory()->create([
            'role' => 'franchise',
            'name' => 'Active Kendra',
            'email' => 'active@kendra.com',
            'phone' => '9876543210',
            'status' => 'active',
        ]);

        $this->inactiveFranchise = User::factory()->create([
            'role' => 'franchise',
            'name' => 'Inactive Kendra',
            'email' => 'inactive@kendra.com',
            'phone' => '9876543211',
            'status' => 'inactive',
        ]);
    }

    public function test_admin_can_view_franchises_with_status_filter_and_switches(): void
    {
        // 1. All franchises view
        $response = $this->actingAs($this->admin)->get(route('admin.franchises'));
        $response->assertStatus(200);
        $response->assertSee('Active Kendra');
        $response->assertSee('Inactive Kendra');
        $response->assertSee('status-toggle-switch');

        // 2. Filter Active only
        $activeResponse = $this->actingAs($this->admin)->get(route('admin.franchises', ['status' => 'active']));
        $activeResponse->assertStatus(200);
        $activeResponse->assertSee('Active Kendra');
        $activeResponse->assertDontSee('Inactive Kendra');

        // 3. Filter Inactive only
        $inactiveResponse = $this->actingAs($this->admin)->get(route('admin.franchises', ['status' => 'inactive']));
        $inactiveResponse->assertStatus(200);
        $inactiveResponse->assertSee('Inactive Kendra');
        $inactiveResponse->assertDontSee('Active Kendra');
    }

    public function test_admin_can_toggle_franchise_status_via_ajax(): void
    {
        // Toggle Active -> Inactive
        $response = $this->actingAs($this->admin)->postJson(
            route('admin.franchises.toggle_status', $this->activeFranchise->id),
            ['status' => 'inactive']
        );

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'inactive',
            'status_label' => 'Inactive',
        ]);

        $this->assertEquals('inactive', $this->activeFranchise->fresh()->status);

        // Toggle Inactive -> Active
        $response2 = $this->actingAs($this->admin)->postJson(
            route('admin.franchises.toggle_status', $this->activeFranchise->id),
            ['status' => 'active']
        );

        $response2->assertStatus(200);
        $response2->assertJson([
            'success' => true,
            'status' => 'active',
            'status_label' => 'Active',
        ]);

        $this->assertEquals('active', $this->activeFranchise->fresh()->status);
    }

    public function test_admin_can_toggle_franchise_status_without_payload(): void
    {
        // Calling toggle without explicit status flips the current status
        $this->assertEquals('active', $this->activeFranchise->status);

        $response = $this->actingAs($this->admin)->postJson(
            route('admin.franchises.toggle_status', $this->activeFranchise->id)
        );

        $response->assertStatus(200);
        $this->assertEquals('inactive', $this->activeFranchise->fresh()->status);
    }
}
