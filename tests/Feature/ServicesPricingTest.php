<?php

namespace Tests\Feature;

use App\Models\FranchiseServicePrice;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServicesPricingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $franchise;
    protected Service $panService;
    protected Service $dlService;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Super Admin user
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
            'category' => 'Identity & Tax',
            'description' => 'Fast real-time PAN card validation',
            'endpoint_path' => '/api/v1/pan/details-v1',
            'http_method' => 'POST',
            'token_cost' => 2.00,
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
            'token_cost' => 3.00,
            'is_active' => true,
            'sample_payload' => ['dlNumber' => 'DL1234567890123', 'dob' => '1990-01-01'],
            'required_fields' => ['dlNumber', 'dob'],
        ]);
    }

    public function test_super_admin_can_view_services_pricing_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.services.pricing'));

        $response->assertStatus(200);
        $response->assertSee('Services Price Management');
        $response->assertSee('Global Service Pricing Matrix');
        $response->assertSee('Pan details V1');
        $response->assertSee('DL Verification Plus (Srv2)');
        $response->assertSee('₹2.00');
        $response->assertSee('₹3.00');
    }

    public function test_super_admin_can_fetch_services_pricing_json(): void
    {
        $response = $this->actingAs($this->admin)->getJson(route('admin.services.pricing'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'stats' => ['total_services', 'active_services', 'avg_price', 'min_price', 'max_price', 'total_overrides'],
            'services' => [
                '*' => ['id', 'name', 'service_code', 'category', 'token_cost', 'is_active', 'endpoint_path', 'franchise_overrides_count']
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

    public function test_super_admin_can_bulk_update_services_prices(): void
    {
        $payload = [
            'prices' => [
                $this->panService->id => 4.50,
                $this->dlService->id => 5.25,
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.services.pricing.update'), $payload);

        $response->assertRedirect(route('admin.services.pricing'));
        $response->assertSessionHas('success');

        $this->assertEquals(4.50, (float) $this->panService->fresh()->token_cost);
        $this->assertEquals(5.25, (float) $this->dlService->fresh()->token_cost);
    }

    public function test_super_admin_can_bulk_update_services_prices_via_json(): void
    {
        $payload = [
            'prices' => [
                $this->panService->id => 3.75,
                $this->dlService->id => 4.25,
            ]
        ];

        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.services.pricing.update'), $payload);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'updated_count' => 2,
        ]);

        $this->assertEquals(3.75, (float) $this->panService->fresh()->token_cost);
        $this->assertEquals(4.25, (float) $this->dlService->fresh()->token_cost);
    }

    public function test_super_admin_can_update_single_service_price(): void
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.services.pricing.single', $this->panService->id), [
                'token_cost' => 6.50,
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'service' => [
                'id' => $this->panService->id,
                'old_token_cost' => 2.00,
                'token_cost' => 6.50,
            ],
        ]);

        $this->assertEquals(6.50, (float) $this->panService->fresh()->token_cost);
    }

    public function test_price_validation_rejects_negative_price(): void
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('admin.services.pricing.single', $this->panService->id), [
                'token_cost' => -5.00,
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['token_cost']);
    }

    public function test_non_admin_cannot_access_services_pricing(): void
    {
        // Franchise user gets 403 Forbidden
        $response = $this->actingAs($this->franchise)->get(route('admin.services.pricing'));
        $response->assertStatus(403);

        $postResponse = $this->actingAs($this->franchise)->post(route('admin.services.pricing.update'), [
            'prices' => [$this->panService->id => 10.00]
        ]);
        $postResponse->assertStatus(403);

        $singlePost = $this->actingAs($this->franchise)->postJson(route('admin.services.pricing.single', $this->panService->id), [
            'token_cost' => 10.00
        ]);
        $singlePost->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.services.pricing'));
        $response->assertRedirect(route('login'));
    }

    public function test_updated_price_reflects_on_services_catalog_page(): void
    {
        // Admin updates price
        $this->actingAs($this->admin)->post(route('admin.services.pricing.single', $this->panService->id), [
            'token_cost' => 7.80,
        ]);

        // Check catalog view
        $response = $this->actingAs($this->admin)->get(route('admin.services'));
        $response->assertStatus(200);
        $response->assertSee('7.80 Tokens');
        $response->assertSee('₹7.80 / hit');
    }

    public function test_services_pricing_alias_redirects(): void
    {
        $response = $this->actingAs($this->admin)->get('/services-pricing');
        $response->assertRedirect(route('admin.services.pricing'));
    }

    public function test_services_page_renders_clean_content_without_subtext_or_transaction_notice(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.services'));
        $response->assertStatus(200);
        $response->assertSee('Our Services');
        $response->assertDontSee('Integrated Service Catalog');
        $response->assertDontSee('lockForUpdate');
        $response->assertDontSeeText('Fast real-time PAN card validation');
        $response->assertDontSeeText('Verify Indian Driving License details');
        $response->assertDontSee('TOTAL DEDUCTION');
        $response->assertDontSee('Base Price:');
    }

    public function test_services_page_renders_enlarged_service_title_boxes(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.services'));
        $response->assertStatus(200);
        $response->assertSee('min-height: 92px', false);
        $response->assertSee('font-size: 1.25rem', false);
        $response->assertSee('width: 52px; height: 52px;', false);
    }
}
