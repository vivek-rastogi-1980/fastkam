<?php

namespace Tests\Feature;

use App\Models\FranchiseServicePrice;
use App\Models\Service;
use App\Models\User;
use App\Services\IdspayClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class SuperAdminPricingTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $franchise;
    protected Service $panService;
    protected Service $dlService;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Super Admin
        $this->superAdmin = User::factory()->create([
            'name' => 'Super Administrator',
            'email' => 'admin@a4Digital.com',
            'role' => 'admin',
            'wallet_balance' => 10000.00,
        ]);

        // 2. Franchise
        $this->franchise = User::factory()->create([
            'name' => 'City Digital Kendra',
            'email' => 'city@a4Digital.com',
            'role' => 'franchise',
            'wallet_balance' => 800.00,
            'api_key' => 'vle_live_city_test_key_123',
            'api_secret' => 'sec_city_test_secret_123',
        ]);

        // 3. Sample Services with distinct Super Admin Cost vs Default Franchise Price
        $this->panService = Service::create([
            'name' => 'Pan details V1',
            'service_code' => 'pan_details_v1',
            'category' => 'Identity & Tax',
            'description' => 'Fast real-time PAN card validation',
            'endpoint_path' => '/api/v1/pan/details-v1',
            'http_method' => 'POST',
            'token_cost' => 1.50,
            'super_admin_cost' => 1.50, // Super admin pays 1.50
            'default_franchise_price' => 5.00, // New franchises charged 5.00
            'is_active' => true,
            'sample_payload' => ['pan' => 'ABCDE1234F'],
            'required_fields' => ['pan'],
        ]);

        $this->dlService = Service::create([
            'name' => 'DL Verification Plus (Srv2)',
            'service_code' => 'dl_verification_plus_srv2',
            'category' => 'Transport & Vahan',
            'description' => 'Verify Indian Driving License details',
            'endpoint_path' => '/api/v1/vahan/dl-verification-plus-srv2',
            'http_method' => 'POST',
            'token_cost' => 2.00,
            'super_admin_cost' => 2.00, // Super admin pays 2.00
            'default_franchise_price' => 8.00, // New franchises charged 8.00
            'is_active' => true,
            'sample_payload' => ['dlNumber' => 'DL1420110012345', 'dob' => '1990-01-01'],
            'required_fields' => ['dlNumber', 'dob'],
        ]);
    }

    public function test_super_admin_can_view_separate_super_admin_pricing_page(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.services.super_admin_pricing'));

        $response->assertStatus(200);
        $response->assertSee('Super Admin Price Management');
        $response->assertSee('Super Admin Service Pricing');
        $response->assertSee('Super Admin Dedicated Pricing');
        $response->assertSee('Pan details V1');
        $response->assertSee('DL Verification Plus (Srv2)');
        $response->assertSee('1.50');
        $response->assertSee('2.00');
    }

    public function test_super_admin_can_fetch_super_admin_pricing_json(): void
    {
        $response = $this->actingAs($this->superAdmin)->getJson(route('admin.services.super_admin_pricing'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'stats' => ['total_services', 'active_services', 'avg_admin_cost', 'min_admin_cost', 'max_admin_cost', 'admin_wallet_balance'],
            'services' => [
                '*' => ['id', 'name', 'service_code', 'category', 'super_admin_cost', 'default_franchise_price', 'is_active']
            ]
        ]);
        $response->assertJson([
            'status' => 'SUCCESS',
            'stats' => [
                'total_services' => 2,
                'active_services' => 2,
            ],
        ]);
    }

    public function test_super_admin_can_update_single_super_admin_price(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->postJson(route('admin.services.super_admin_pricing.single', $this->panService->id), [
                'super_admin_cost' => 1.80,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'service' => [
                'id' => $this->panService->id,
                'super_admin_cost' => 1.80,
            ],
        ]);

        $this->panService->refresh();
        $this->assertEquals(1.80, (float) $this->panService->super_admin_cost);
        // Ensure default franchise price was untouched
        $this->assertEquals(5.00, (float) $this->panService->default_franchise_price);
    }

    public function test_super_admin_can_bulk_update_super_admin_prices(): void
    {
        $payload = [
            'prices' => [
                $this->panService->id => 1.25,
                $this->dlService->id => 2.50,
            ]
        ];

        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.services.super_admin_pricing.update'), $payload);

        $response->assertRedirect(route('admin.services.super_admin_pricing'));
        $response->assertSessionHas('success');

        $this->assertEquals(1.25, (float) $this->panService->fresh()->super_admin_cost);
        $this->assertEquals(2.50, (float) $this->dlService->fresh()->super_admin_cost);
    }

    public function test_non_admin_cannot_access_super_admin_pricing(): void
    {
        $response = $this->actingAs($this->franchise)->get(route('admin.services.super_admin_pricing'));
        $response->assertStatus(403);

        $postResponse = $this->actingAs($this->franchise)->post(route('admin.services.super_admin_pricing.update'), [
            'prices' => [$this->panService->id => 0.50]
        ]);
        $postResponse->assertStatus(403);
    }

    public function test_default_price_is_applicable_for_new_franchise_only(): void
    {
        // 1. Existing franchise has a custom negotiated price of 4.20 for panService
        FranchiseServicePrice::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'price' => 4.20,
        ]);

        // 2. Admin updates the Default Price for new franchises to 9.50
        $updateResponse = $this->actingAs($this->superAdmin)
            ->post(route('admin.services.pricing.single', $this->panService->id), [
                'token_cost' => 9.50,
            ]);
        $updateResponse->assertRedirect();

        $this->panService->refresh();
        $this->assertEquals(9.50, (float) $this->panService->default_franchise_price);

        // 3. Verify existing franchise's price remains 4.20 (NOT modified!)
        $existingFranchisePrice = FranchiseServicePrice::where('user_id', $this->franchise->id)
            ->where('service_id', $this->panService->id)
            ->value('price');
        $this->assertEquals(4.20, (float) $existingFranchisePrice);

        // 4. Verify Super Admin Price remains 1.50 (NOT modified!)
        $this->assertEquals(1.50, (float) $this->panService->super_admin_cost);

        // 5. Now register a NEW franchise
        $newFranchisePayload = [
            'name' => 'Surya Seva Kendra',
            'full_name' => 'Surya Pratap Singh',
            'email' => 'surya@seva.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'phone' => '9876543210',
            'address' => 'Station Road, Lucknow',
            'dob' => '1992-05-15',
            'gov_id_type' => 'aadhaar',
            'gov_id_number' => '998877665544',
            'initial_tokens' => 300.00,
        ];

        $registerResponse = $this->actingAs($this->superAdmin)
            ->post(route('admin.franchises.store'), $newFranchisePayload);
        $registerResponse->assertRedirect(route('admin.franchises'));

        $newFranchise = User::where('email', 'surya@seva.com')->first();
        $this->assertNotNull($newFranchise);

        // 6. The newly registered franchise inherits the new Default Price (9.50)
        $this->assertDatabaseHas('franchise_service_prices', [
            'user_id' => $newFranchise->id,
            'service_id' => $this->panService->id,
            'price' => 9.50,
        ]);
    }

    public function test_super_admin_and_franchise_see_their_respective_prices_on_services_catalog(): void
    {
        // Franchise assigned price of 6.50
        FranchiseServicePrice::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'price' => 6.50,
        ]);

        // When Super Admin views services, they see Super Admin Price (1.50)
        $adminView = $this->actingAs($this->superAdmin)->get(route('admin.services'));
        $adminView->assertStatus(200);
        $adminView->assertSee('1.50 Tokens');

        // When Franchise views services, they see Franchise Price (6.50)
        $franchiseView = $this->actingAs($this->franchise)->get(route('admin.services'));
        $franchiseView->assertStatus(200);
        $franchiseView->assertSee('6.50 Tokens');
    }

    public function test_dual_deduction_uses_franchise_assigned_price_and_super_admin_price(): void
    {
        // Super Admin cost = 2.00, Franchise assigned price = 12.00
        FranchiseServicePrice::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->dlService->id,
            'price' => 12.00,
        ]);

        $mockIdspay = Mockery::mock(IdspayClient::class);
        $mockIdspay->shouldReceive('dispatch')
            ->once()
            ->andReturn([
                'success' => true,
                'status' => 'SUCCESS',
                'http_code' => 200,
                'data' => [
                    'status' => 'SUCCESS',
                    'transaction_id' => 'TXN_DL_SEP_TEST',
                    'dlNumber' => 'DL1420110012345',
                ],
                'execution_time_ms' => 120,
            ]);

        $this->app->instance(IdspayClient::class, $mockIdspay);

        $franchiseInitial = (float) $this->franchise->wallet_balance; // 800.00
        $adminInitial = (float) $this->superAdmin->wallet_balance; // 10000.00

        // Franchise hits the service
        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchise->api_key,
        ])->postJson(route('api.service.execute', 'dl_verification_plus_srv2'), [
            'dlNumber' => 'DL1420110012345',
            'dob' => '1990-01-01',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'billing' => [
                'tokens_deducted' => 12.00,
                'previous_balance' => 800.00,
                'remaining_balance' => 788.00,
            ],
        ]);

        // Franchise deducted by their price (12.00)
        $this->assertEquals(788.00, (float) $this->franchise->fresh()->wallet_balance);

        // Super Admin deducted by super_admin_cost (2.00)
        $this->assertEquals(9998.00, (float) $this->superAdmin->fresh()->wallet_balance);
    }
}
