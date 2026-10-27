<?php

namespace Tests\Feature;

use App\Models\FranchiseServicePrice;
use App\Models\Service;
use App\Models\User;
use App\Services\IdspayClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class FranchiseServiceCostTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $franchise;
    protected Service $panService;
    protected Service $dlService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Admin user
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'wallet_balance' => 10000.00,
        ]);

        // Create Franchise user
        $this->franchise = User::factory()->create([
            'role' => 'franchise',
            'wallet_balance' => 500.00,
            'api_key' => 'vle_live_franchise_test_123',
            'api_secret' => 'sec_franchise_test_123',
        ]);

        // Seed sample services
        $this->panService = Service::create([
            'name' => 'Pan details V1',
            'service_code' => 'pan_details_v1',
            'category' => 'PAN Services',
            'description' => 'Fast real-time PAN card validation',
            'endpoint_path' => '/api/pan/v1',
            'http_method' => 'POST',
            'token_cost' => 2.00,
            'is_active' => true,
            'sample_payload' => ['pan' => 'ABCDE1234F'],
            'required_fields' => ['pan'],
        ]);

        $this->dlService = Service::create([
            'name' => 'DL Verification Plus (Srv2)',
            'service_code' => 'dl_verification_plus_srv2',
            'category' => 'Transport',
            'description' => 'Verify Indian Driving License details',
            'endpoint_path' => '/api/dl/v2',
            'http_method' => 'POST',
            'token_cost' => 3.00,
            'is_active' => true,
            'sample_payload' => ['dlNumber' => 'DL1234567890123', 'dob' => '1990-01-01'],
            'required_fields' => ['dlNumber', 'dob'],
        ]);
    }

    public function test_admin_can_view_services_cost_page_and_json(): void
    {
        // 1. Web Page view
        $response = $this->actingAs($this->admin)->get(route('admin.franchises.services_cost', $this->franchise->id));
        $response->assertStatus(200);
        $response->assertSee('Services Cost: ' . $this->franchise->name);
        $response->assertSee('Pan details V1');
        $response->assertSee('DL Verification Plus (Srv2)');

        // 2. AJAX JSON view
        $jsonResponse = $this->actingAs($this->admin)->getJson(route('admin.franchises.services_cost', $this->franchise->id));
        $jsonResponse->assertStatus(200);
        $jsonResponse->assertJsonStructure([
            'status',
            'franchise' => ['id', 'name', 'email'],
            'services' => [
                '*' => ['id', 'name', 'service_code', 'default_price', 'custom_price', 'effective_price']
            ]
        ]);
    }

    public function test_admin_can_add_custom_prices_against_each_service_for_franchise(): void
    {
        $payload = [
            'prices' => [
                $this->panService->id => 4.50,
                $this->dlService->id => 6.00,
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.franchises.services_cost.update', $this->franchise->id), $payload);

        $response->assertRedirect(route('admin.franchises'));
        $response->assertSessionHas('success');

        // Check database
        $this->assertDatabaseHas('franchise_service_prices', [
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'price' => 4.50,
        ]);

        $this->assertDatabaseHas('franchise_service_prices', [
            'user_id' => $this->franchise->id,
            'service_id' => $this->dlService->id,
            'price' => 6.00,
        ]);
    }

    public function test_admin_can_update_these_prices_in_future(): void
    {
        // Initial price set
        FranchiseServicePrice::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'price' => 4.50,
        ]);

        // Future update by admin
        $payload = [
            'prices' => [
                $this->panService->id => 7.25,
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.franchises.services_cost.update', $this->franchise->id), $payload);

        $response->assertRedirect(route('admin.franchises'));

        $this->assertDatabaseHas('franchise_service_prices', [
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'price' => 7.25,
        ]);
    }

    public function test_when_franchise_logs_in_all_these_prices_are_reflected_on_services_page(): void
    {
        // Admin sets custom price: PAN = 5.50 (default was 2.00)
        FranchiseServicePrice::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'price' => 5.50,
        ]);

        // Franchise logs in
        $response = $this->actingAs($this->franchise)->get(route('admin.services'));
        $response->assertStatus(200);

        // Should reflect 5.50 Tokens for Pan Service
        $response->assertSee('5.50 Tokens');
        $response->assertSee('₹5.50 / hit');
        $response->assertSee('data-cost="5.50"', false);

        // DL service had no custom price, should reflect default 3.00 Tokens
        $response->assertSee('3.00 Tokens');
        $response->assertSee('₹3.00 / hit');
    }

    public function test_service_execution_deducts_custom_franchise_price(): void
    {
        // Admin sets custom price: PAN = 8.00 (default was 2.00)
        FranchiseServicePrice::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'price' => 8.00,
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
                    'transaction_id' => 'GATEWAY_TXN_999',
                    'pan' => 'ABCDE1234F',
                    'name' => 'JOHN DOE',
                ],
                'execution_time_ms' => 120,
            ]);

        $this->app->instance(IdspayClient::class, $mockIdspay);

        $initialBalance = (float) $this->franchise->wallet_balance; // 500.00

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchise->api_key,
        ])->postJson(route('api.service.execute', 'pan_details_v1'), [
            'pan' => 'ABCDE1234F',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'billing' => [
                'tokens_deducted' => 8.00,
                'remaining_balance' => $initialBalance - 8.00,
            ],
        ]);

        // Verify wallet balance is 500 - 8 = 492
        $this->assertEquals($initialBalance - 8.00, (float) $this->franchise->fresh()->wallet_balance);

        // Verify wallet transaction record
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->franchise->id,
            'type' => 'debit',
            'amount' => 8.00,
        ]);
    }

    public function test_franchise_without_custom_price_uses_default_catalog_price(): void
    {
        // Create second franchise without any custom prices
        $secondFranchise = User::factory()->create([
            'role' => 'franchise',
            'wallet_balance' => 500.00,
            'api_key' => 'vle_live_second_999',
            'api_secret' => 'sec_second_999',
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
                    'transaction_id' => 'GATEWAY_TXN_888',
                    'pan' => 'ABCDE1234F',
                ],
                'execution_time_ms' => 100,
            ]);

        $this->app->instance(IdspayClient::class, $mockIdspay);

        $response = $this->withHeaders([
            'X-API-KEY' => $secondFranchise->api_key,
        ])->postJson(route('api.service.execute', 'pan_details_v1'), [
            'pan' => 'ABCDE1234F',
        ]);

        $response->assertStatus(200);
        // Default PAN cost is 2.00
        $response->assertJson([
            'status' => 'SUCCESS',
            'billing' => [
                'tokens_deducted' => 2.00,
            ],
        ]);

        $this->assertEquals(498.00, (float) $secondFranchise->fresh()->wallet_balance);
    }

    public function test_non_admin_cannot_access_services_cost_routes(): void
    {
        $response = $this->actingAs($this->franchise)->get(route('admin.franchises.services_cost', $this->franchise->id));
        $response->assertStatus(403);

        $postResponse = $this->actingAs($this->franchise)->post(route('admin.franchises.services_cost.update', $this->franchise->id), [
            'prices' => [$this->panService->id => 1.00]
        ]);
        $postResponse->assertStatus(403);
    }

    /**
     * Test when a new franchise is added, default prices for each service are created and applicable at creation.
     */
    public function test_new_franchise_creation_automatically_assigns_default_prices_for_each_service(): void
    {
        $payload = [
            'name' => 'Kisan Seva Kendra',
            'full_name' => 'Suresh Patel',
            'email' => 'suresh@kisanseva.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'phone' => '9822334455',
            'address' => 'Shop 10, Village Chowk, Gujarat',
            'dob' => '1995-04-10',
            'gov_id_type' => 'aadhaar',
            'gov_id_number' => '123456789012',
            'initial_tokens' => 200.00,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.franchises.store'), $payload);
        $response->assertRedirect(route('admin.franchises'));

        $newFranchise = User::where('email', 'suresh@kisanseva.com')->first();
        $this->assertNotNull($newFranchise);

        // Verify that default prices for each service are explicitly created in franchise_service_prices
        $this->assertDatabaseHas('franchise_service_prices', [
            'user_id' => $newFranchise->id,
            'service_id' => $this->panService->id,
            'price' => 2.00, // Default token_cost of panService
        ]);

        $this->assertDatabaseHas('franchise_service_prices', [
            'user_id' => $newFranchise->id,
            'service_id' => $this->dlService->id,
            'price' => 3.00, // Default token_cost of dlService
        ]);

        // When new franchise logs in and views services, default prices are reflected and GST is not charged
        $servicesResponse = $this->actingAs($newFranchise)->get(route('admin.services'));
        $servicesResponse->assertStatus(200);
        $servicesResponse->assertSee('2.00 Tokens');
        $servicesResponse->assertSee('3.00 Tokens');
        $servicesResponse->assertDontSee('+ 18% GST');
    }

    /**
     * Test main administrator can manage default prices and they are applicable to new franchises upon creation.
     */
    public function test_main_admin_can_manage_default_prices_and_they_apply_to_new_franchises(): void
    {
        // 1. In Main Administrator Panel, admin updates default price of panService to 7.50
        $updateResponse = $this->actingAs($this->admin)->post(route('admin.services.pricing.single', $this->panService->id), [
            'token_cost' => 7.50,
        ]);
        $updateResponse->assertRedirect();

        $this->panService->refresh();
        $this->assertEquals(7.50, (float) $this->panService->token_cost);

        // 2. Admin adds a new franchise
        $payload = [
            'name' => 'Metro Digital Hub',
            'full_name' => 'Anil Sharma',
            'email' => 'anil@metrodigital.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'phone' => '9988776655',
            'address' => 'Shop 5, Metro Station, Delhi',
            'dob' => '1991-07-22',
            'gov_id_type' => 'pan',
            'gov_id_number' => 'ABCDE9876Z',
            'initial_tokens' => 500.00,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.franchises.store'), $payload);
        $response->assertRedirect(route('admin.franchises'));

        $metroFranchise = User::where('email', 'anil@metrodigital.com')->first();
        $this->assertNotNull($metroFranchise);

        // Verify the newly updated default price (7.50) is applied to this new franchise at creation
        $this->assertDatabaseHas('franchise_service_prices', [
            'user_id' => $metroFranchise->id,
            'service_id' => $this->panService->id,
            'price' => 7.50,
        ]);
    }

    /**
     * Test franchise can be created without personal KYC fields and initial tokens.
     */
    public function test_franchise_can_be_created_without_kyc_and_initial_tokens(): void
    {
        $payload = [
            'name' => 'Speedy Digital Kendra',
            'full_name' => 'Vikram Singh',
            'email' => 'vikram@speedy.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'phone' => '9811223344',
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.franchises.store'), $payload);
        $response->assertRedirect(route('admin.franchises'));

        $franchise = User::where('email', 'vikram@speedy.com')->first();
        $this->assertNotNull($franchise);
        $this->assertNull($franchise->dob);
        $this->assertNull($franchise->gov_id_type);
        $this->assertNull($franchise->gov_id_number);
        $this->assertNull($franchise->address);
        $this->assertEquals(0.00, (float) $franchise->wallet_balance);

        // Should render index page without error
        $indexResponse = $this->actingAs($this->admin)->get(route('admin.franchises'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Speedy Digital Kendra');
        $indexResponse->assertSee('Not provided');
    }
}

