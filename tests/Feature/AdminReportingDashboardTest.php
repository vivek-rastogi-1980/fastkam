<?php

namespace Tests\Feature;

use App\Models\ApiLog;
use App\Models\Service;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminReportingDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $franchise;
    protected Service $panService;
    protected Service $dlService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Super Administrator',
            'email' => 'admin@a4Digital.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'wallet_balance' => 50000.00,
        ]);

        $this->franchise = User::create([
            'name' => 'Apex Kendra',
            'full_name' => 'Rajesh Sharma',
            'email' => 'apex@a4Digital.com',
            'password' => Hash::make('franchise123'),
            'role' => 'franchise',
            'wallet_balance' => 1500.00,
            'status' => 'active',
        ]);

        $this->panService = Service::create([
            'name' => 'Pan Details V2',
            'service_code' => 'pan_details_v2',
            'category' => 'Identity & Tax',
            'description' => 'PAN verification',
            'endpoint_path' => '/api/v1/pan/details-v2',
            'http_method' => 'POST',
            'token_cost' => 2.50,
            'is_active' => true,
        ]);

        $this->dlService = Service::create([
            'name' => 'DL Verification Plus (Srv2)',
            'service_code' => 'dl_verification_plus_srv2',
            'category' => 'Transport & Vahan',
            'description' => 'Driving License verification',
            'endpoint_path' => '/api/v1/vahan/dl-verification-plus-srv2',
            'http_method' => 'POST',
            'token_cost' => 3.00,
            'is_active' => true,
        ]);

        SystemSetting::set('gst_rate_percent', 18.00, 'float', 'tax');
        SystemSetting::set('gst_enabled', true, 'boolean', 'tax');
    }

    /**
     * Test super admin can access reports dashboard view.
     */
    public function test_super_admin_can_view_reporting_dashboard(): void
    {
        ApiLog::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'service_code' => 'pan_details_v2',
            'tokens_deducted' => 2.50,
            'status' => 'SUCCESS',
            'execution_time_ms' => 120,
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get('/reports');

        $response->assertStatus(200);
        $response->assertSee('Executive Reporting & Intelligence');
        $response->assertSee('Operations');
        $response->assertSee('Pan Details V2');
        $response->assertSee('Apex Kendra');
        $response->assertSee('trafficTimelineChart');
        $response->assertSee('servicePieChart');
    }

    /**
     * Test franchise user cannot access reporting dashboard.
     */
    public function test_franchise_cannot_access_reporting_dashboard(): void
    {
        $response = $this->actingAs($this->franchise)->get('/reports');

        // Middleware returns 403 Forbidden for non-admin
        $response->assertStatus(403);
    }

    /**
     * Test guest is redirected to login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/reports');
        $response->assertRedirect('/login');
    }

    /**
     * Test super admin can fetch reporting dashboard json.
     */
    public function test_super_admin_can_fetch_reporting_json(): void
    {
        ApiLog::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'service_code' => 'pan_details_v2',
            'tokens_deducted' => 2.50,
            'status' => 'SUCCESS',
            'execution_time_ms' => 110,
            'created_at' => now(),
        ]);

        WalletTransaction::create([
            'user_id' => $this->franchise->id,
            'transaction_id' => 'TXN_TEST_101',
            'type' => 'credit',
            'amount' => 500.00,
            'opening_balance' => 1000.00,
            'closing_balance' => 1500.00,
            'description' => 'Test recharge',
        ]);

        $response = $this->actingAs($this->admin)->getJson('/reports');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'date_range' => ['text', 'value', 'from', 'to'],
                'kpis' => [
                    'total_logs',
                    'success_logs',
                    'failed_logs',
                    'success_rate',
                    'total_tokens_deducted',
                    'avg_latency',
                    'total_franchises',
                    'active_franchises',
                    'total_wallet_float',
                    'gst_rate',
                ],
                'chart_data' => [
                    'labels',
                    'success',
                    'failed',
                    'tokens',
                    'pie_labels',
                    'pie_calls',
                ],
                'services_breakdown',
                'franchise_leaderboard',
            ]);
    }

    /**
     * Test date range and franchise filtering on reports.
     */
    public function test_reporting_filters_date_range_and_franchise(): void
    {
        // Franchise 2
        $franchise2 = User::create([
            'name' => 'Beta Kendra',
            'email' => 'beta@a4Digital.com',
            'password' => Hash::make('password123'),
            'role' => 'franchise',
            'wallet_balance' => 800.00,
        ]);

        // Old log for franchise 1 (outside 7 days)
        ApiLog::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'service_code' => 'pan_details_v2',
            'tokens_deducted' => 2.50,
            'status' => 'SUCCESS',
            'created_at' => now()->subDays(15),
        ]);

        // Recent log for franchise 2 (today)
        ApiLog::create([
            'user_id' => $franchise2->id,
            'service_id' => $this->dlService->id,
            'service_code' => 'dl_verification_plus_srv2',
            'tokens_deducted' => 3.00,
            'status' => 'SUCCESS',
            'created_at' => now(),
        ]);

        // Query with preset=last7 and franchise_id of franchise2
        $response = $this->actingAs($this->admin)->getJson('/reports?preset=last7&franchise_id=' . $franchise2->id);

        $response->assertStatus(200);
        $data = $response->json();

        $this->assertEquals(1, $data['kpis']['total_logs']);
        $this->assertEquals(3.00, $data['kpis']['total_tokens_deducted']);
    }

    /**
     * Test dashboard redirect routes admin to reports and franchise to services.
     */
    public function test_dashboard_redirects_appropriately(): void
    {
        $adminResp = $this->actingAs($this->admin)->get('/dashboard');
        $adminResp->assertRedirect('/reports');

        $franchiseResp = $this->actingAs($this->franchise)->get('/dashboard');
        $franchiseResp->assertRedirect('/services');

        $aliasResp = $this->actingAs($this->admin)->get('/reporting-dashboard');
        $aliasResp->assertRedirect('/reports');
    }
}
