<?php

namespace Tests\Feature;

use App\Models\FranchiseServicePrice;
use App\Models\Service;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\IdspayClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GstRateDeductionTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $franchiseUser;
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

        // 2. Franchise User
        $this->franchiseUser = User::factory()->create([
            'name' => 'Rasraj Seva Kendra',
            'email' => 'rasraj@a4Digital.com',
            'role' => 'franchise',
            'wallet_balance' => 500.00,
            'api_key' => 'vle_live_rasraj_gst_test_key_123',
            'api_secret' => 'sec_rasraj_gst_test_secret_123',
        ]);

        // 3. Service DL Verification Plus (Srv2)
        $this->dlService = Service::create([
            'name' => 'DL Verification Plus (Srv2)',
            'service_code' => 'dl_verification_plus_srv2',
            'category' => 'Transport & Vahan',
            'description' => 'Verify Indian Driving License details',
            'endpoint_path' => '/api/v1/vahan/dl-verification-plus-srv2',
            'http_method' => 'POST',
            'token_cost' => 4.00,
            'is_active' => true,
            'sample_payload' => ['dlNumber' => 'DL1420110012345', 'dob' => '1990-01-01'],
            'required_fields' => ['dlNumber', 'dob'],
        ]);
    }

    /**
     * Test Super Admin can view GST settings page.
     */
    public function test_super_admin_can_view_gst_settings_page(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.settings.gst'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.settings.gst');
        $response->assertSee('GST Rate & Wallet Tax Settings', false);
    }

    /**
     * Test Franchise cannot access GST settings page.
     */
    public function test_franchise_cannot_access_gst_settings_page(): void
    {
        $response = $this->actingAs($this->franchiseUser)
            ->get(route('admin.settings.gst'));

        $response->assertStatus(403);
    }

    /**
     * Test Guest cannot access GST settings page.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.settings.gst'));

        $response->assertRedirect(route('login'));
    }

    /**
     * Test Super Admin can update GST Rate via form submission.
     */
    public function test_super_admin_can_update_gst_rate(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post(route('admin.settings.gst.update'), [
                'gst_rate_percent' => '18.00',
                'gst_enabled' => '1',
            ]);

        $response->assertRedirect(route('admin.settings.gst'));
        $response->assertSessionHas('success');

        $this->assertEquals(18.00, SystemSetting::getGstRate());
        $this->assertTrue(SystemSetting::isGstEnabled());
    }

    /**
     * Test Service trigger does NOT charge GST to franchise wallet (franchise panel is 0% GST exempt).
     * Example: Price 30.00 Tokens is deducted without GST, even when system GST is configured at 18%.
     */
    public function test_service_trigger_does_not_charge_gst_to_franchise_wallet(): void
    {
        // Configure 18% GST globally
        SystemSetting::set('gst_rate_percent', 18.00);
        SystemSetting::set('gst_enabled', '1');

        // Assign 30 Tokens price for this franchise
        FranchiseServicePrice::create([
            'user_id' => $this->franchiseUser->id,
            'service_id' => $this->dlService->id,
            'price' => 30.00,
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
                    'transaction_id' => 'GW_TXN_GST_101',
                    'dlNumber' => 'DL1420110012345',
                    'holder_name' => 'CITIZEN WITH GST',
                ],
                'execution_time_ms' => 120,
            ]);

        $this->app->instance(IdspayClient::class, $mockIdspay);

        $initialFranchiseBalance = (float) $this->franchiseUser->wallet_balance; // 500.00
        $initialAdminBalance = (float) $this->superAdmin->wallet_balance; // 10000.00

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchiseUser->api_key,
        ])->postJson(route('api.service.execute', 'dl_verification_plus_srv2'), [
            'dlNumber' => 'DL1420110012345',
            'dob' => '1990-01-01',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'billing' => [
                'service_price' => 30.00,
                'gst_rate_percent' => 0.00,
                'gst_amount' => 0.00,
                'tokens_deducted' => 30.00,
                'remaining_balance' => 470.00,
            ],
        ]);

        // Verify franchise wallet deducted by exact 30.00 (NO GST charged to franchise)
        $this->franchiseUser->refresh();
        $this->assertEquals(470.00, (float) $this->franchiseUser->wallet_balance);

        // Verify super admin wallet deducted by base cost (4.00)
        $this->superAdmin->refresh();
        $this->assertEquals(9996.00, (float) $this->superAdmin->wallet_balance);

        // Verify wallet transaction record for franchise
        $franchiseTxn = WalletTransaction::where('user_id', $this->franchiseUser->id)
            ->where('type', 'debit')
            ->latest('id')
            ->first();

        $this->assertNotNull($franchiseTxn);
        $this->assertEquals(30.00, (float) $franchiseTxn->amount);
        $this->assertEquals(500.00, (float) $franchiseTxn->opening_balance);
        $this->assertEquals(470.00, (float) $franchiseTxn->closing_balance);
        $this->assertStringContainsString('30 tokens', $franchiseTxn->description);
        $this->assertEquals(0.00, $franchiseTxn->metadata['gst_amount']);
        $this->assertEquals(0.00, $franchiseTxn->metadata['gst_rate_percent']);
    }

    /**
     * Test Service trigger with 0% GST only deducts the base service price.
     */
    public function test_service_trigger_with_zero_gst_deducts_exact_service_price(): void
    {
        // 0% GST
        SystemSetting::set('gst_rate_percent', 0.00);
        SystemSetting::set('gst_enabled', '1');

        FranchiseServicePrice::create([
            'user_id' => $this->franchiseUser->id,
            'service_id' => $this->dlService->id,
            'price' => 30.00,
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
                    'transaction_id' => 'GW_TXN_ZERO_GST',
                ],
                'execution_time_ms' => 100,
            ]);

        $this->app->instance(IdspayClient::class, $mockIdspay);

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchiseUser->api_key,
        ])->postJson(route('api.service.execute', 'dl_verification_plus_srv2'), [
            'dlNumber' => 'DL1420110012345',
            'dob' => '1990-01-01',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'billing' => [
                'service_price' => 30.00,
                'gst_rate_percent' => 0.00,
                'gst_amount' => 0.00,
                'tokens_deducted' => 30.00,
                'remaining_balance' => 470.00,
            ],
        ]);

        $this->franchiseUser->refresh();
        $this->assertEquals(470.00, (float) $this->franchiseUser->wallet_balance);
    }

    /**
     * Test Service trigger with disabled GST charges 0% GST.
     */
    public function test_service_trigger_with_disabled_gst(): void
    {
        SystemSetting::set('gst_rate_percent', 18.00);
        SystemSetting::set('gst_enabled', '0'); // disabled

        FranchiseServicePrice::create([
            'user_id' => $this->franchiseUser->id,
            'service_id' => $this->dlService->id,
            'price' => 30.00,
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
                ],
                'execution_time_ms' => 100,
            ]);

        $this->app->instance(IdspayClient::class, $mockIdspay);

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchiseUser->api_key,
        ])->postJson(route('api.service.execute', 'dl_verification_plus_srv2'), [
            'dlNumber' => 'DL1420110012345',
            'dob' => '1990-01-01',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'billing' => [
                'service_price' => 30.00,
                'gst_amount' => 0.00,
                'tokens_deducted' => 30.00,
            ],
        ]);
    }

    /**
     * Test insufficient balance check for franchise requires exact service price (no GST added).
     * If user has 25 Tokens and base price is 30, execution must fail requiring 30.00 tokens.
     */
    public function test_insufficient_balance_check_for_franchise_requires_service_price(): void
    {
        SystemSetting::set('gst_rate_percent', 18.00);
        SystemSetting::set('gst_enabled', '1');

        $this->franchiseUser->wallet_balance = 25.00;
        $this->franchiseUser->save();

        FranchiseServicePrice::create([
            'user_id' => $this->franchiseUser->id,
            'service_id' => $this->dlService->id,
            'price' => 30.00,
        ]);

        $mockIdspay = Mockery::mock(IdspayClient::class);
        $mockIdspay->shouldNotReceive('dispatch');
        $this->app->instance(IdspayClient::class, $mockIdspay);

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchiseUser->api_key,
        ])->postJson(route('api.service.execute', 'dl_verification_plus_srv2'), [
            'dlNumber' => 'DL1420110012345',
            'dob' => '1990-01-01',
        ]);

        $response->assertStatus(402);
        $response->assertJson([
            'status' => 'FAILED',
            'response_code' => '402',
            'required_tokens' => 30.00,
            'service_price' => 30.00,
            'gst_rate_percent' => 0.00,
            'gst_amount' => 0.00,
        ]);

        $this->franchiseUser->refresh();
        $this->assertEquals(25.00, (float) $this->franchiseUser->wallet_balance);
    }

    /**
     * Test franchise panel services page does NOT display or charge GST.
     */
    public function test_franchise_panel_services_page_does_not_display_gst(): void
    {
        SystemSetting::set('gst_rate_percent', 18.00);
        SystemSetting::set('gst_enabled', '1');

        FranchiseServicePrice::create([
            'user_id' => $this->franchiseUser->id,
            'service_id' => $this->dlService->id,
            'price' => 30.00,
        ]);

        $response = $this->actingAs($this->franchiseUser)->get(route('admin.services'));
        $response->assertStatus(200);

        // Franchise sees exact tokens without GST surcharge
        $response->assertSee('30.00 Tokens');
        $response->assertDontSee('+ 18% GST');
    }
}
