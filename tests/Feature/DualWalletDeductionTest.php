<?php

namespace Tests\Feature;

use App\Models\FranchiseServicePrice;
use App\Models\Service;
use App\Models\User;
use App\Services\IdspayClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class DualWalletDeductionTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $rasrajFranchise;
    protected Service $dlService;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Super Admin account with 10,000.00 Tokens
        $this->superAdmin = User::factory()->create([
            'name' => 'Super Administrator',
            'email' => 'admin@a4Digital.com',
            'role' => 'admin',
            'wallet_balance' => 10000.00,
        ]);

        // 2. Franchise "Rasraj Seva Kendra" with 500.00 Tokens
        $this->rasrajFranchise = User::factory()->create([
            'name' => 'rasraj seva kendra',
            'email' => 'rasraj@a4Digital.com',
            'role' => 'franchise',
            'wallet_balance' => 500.00,
            'api_key' => 'vle_live_rasraj_test_key_123',
            'api_secret' => 'sec_rasraj_test_secret_123',
        ]);

        // 3. "DL Verification Plus (Srv2)" service with base catalog cost = 4.00 Tokens
        $this->dlService = Service::create([
            'name' => 'DL Verification Plus (Srv2)',
            'service_code' => 'dl_verification_plus_srv2',
            'category' => 'Transport & Vahan',
            'description' => 'Verify Indian Driving License details',
            'endpoint_path' => '/api/v1/vahan/dl-verification-plus-srv2',
            'http_method' => 'POST',
            'token_cost' => 4.00, // Super admin base assign amount = 4 Tokens
            'is_active' => true,
            'sample_payload' => ['dlNumber' => 'DL1420110012345', 'dob' => '1990-01-01'],
            'required_fields' => ['dlNumber', 'dob'],
        ]);
    }

    /**
     * Exact scenario from user:
     * Rasraj seva kendra triggers "DL Verification Plus (Srv2)" (assigned 30 tokens).
     * Rasraj seva kendra wallet is deducted by 30 tokens.
     * Super admin wallet is deducted by 4 tokens.
     */
    public function test_franchise_hit_deducts_assigned_price_from_franchise_and_base_price_from_super_admin(): void
    {
        // Super admin assigns custom price of 30.00 tokens to Rasraj Seva Kendra
        FranchiseServicePrice::create([
            'user_id' => $this->rasrajFranchise->id,
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
                    'transaction_id' => 'GATEWAY_TXN_DL_999',
                    'dlNumber' => 'DL1420110012345',
                    'holder_name' => 'RASRAJ CITIZEN',
                ],
                'execution_time_ms' => 150,
            ]);

        $this->app->instance(IdspayClient::class, $mockIdspay);

        $franchiseInitial = (float) $this->rasrajFranchise->wallet_balance; // 500.00
        $adminInitial = (float) $this->superAdmin->wallet_balance; // 10000.00

        // Franchise hits the service
        $response = $this->withHeaders([
            'X-API-KEY' => $this->rasrajFranchise->api_key,
        ])->postJson(route('api.service.execute', 'dl_verification_plus_srv2'), [
            'dlNumber' => 'DL1420110012345',
            'dob' => '1990-01-01',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'billing' => [
                'tokens_deducted' => 30.00,
                'previous_balance' => 500.00,
                'remaining_balance' => 470.00,
            ],
        ]);

        // 1. Franchise wallet deducted by 30.00 Tokens (500 - 30 = 470)
        $this->assertEquals(470.00, (float) $this->rasrajFranchise->fresh()->wallet_balance);

        // 2. Super Admin wallet deducted by 4.00 Tokens (10000 - 4 = 9996)
        $this->assertEquals(9996.00, (float) $this->superAdmin->fresh()->wallet_balance);

        // 3. Verify Franchise Wallet Transaction record (Debit 30.00)
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->rasrajFranchise->id,
            'service_id' => $this->dlService->id,
            'type' => 'debit',
            'amount' => 30.00,
            'opening_balance' => 500.00,
            'closing_balance' => 470.00,
        ]);

        // 4. Verify Super Admin Wallet Transaction record (Debit 4.00)
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->superAdmin->id,
            'service_id' => $this->dlService->id,
            'type' => 'debit',
            'amount' => 4.00,
            'opening_balance' => 10000.00,
            'closing_balance' => 9996.00,
        ]);
    }

    /**
     * If Super Admin triggers service directly, only Super Admin wallet is deducted by base amount.
     */
    public function test_super_admin_direct_trigger_deducts_only_super_admin_wallet(): void
    {
        // Give Super Admin an API key for testing direct execution
        $this->superAdmin->api_key = 'vle_live_super_admin_test_key';
        $this->superAdmin->save();

        $mockIdspay = Mockery::mock(IdspayClient::class);
        $mockIdspay->shouldReceive('dispatch')
            ->once()
            ->andReturn([
                'success' => true,
                'status' => 'SUCCESS',
                'http_code' => 200,
                'data' => [
                    'status' => 'SUCCESS',
                    'transaction_id' => 'ADMIN_DIRECT_TXN_1',
                    'dlNumber' => 'DL1420110012345',
                ],
                'execution_time_ms' => 110,
            ]);

        $this->app->instance(IdspayClient::class, $mockIdspay);

        $adminInitial = (float) $this->superAdmin->wallet_balance; // 10000.00
        $franchiseInitial = (float) $this->rasrajFranchise->wallet_balance; // 500.00

        $response = $this->withHeaders([
            'X-API-KEY' => $this->superAdmin->api_key,
        ])->postJson(route('api.service.execute', 'dl_verification_plus_srv2'), [
            'dlNumber' => 'DL1420110012345',
            'dob' => '1990-01-01',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'SUCCESS',
            'billing' => [
                'tokens_deducted' => 4.00,
                'remaining_balance' => $adminInitial - 4.00,
            ],
        ]);

        // Super Admin deducted 4.00 tokens
        $this->assertEquals($adminInitial - 4.00, (float) $this->superAdmin->fresh()->wallet_balance);

        // Franchise wallet untouched
        $this->assertEquals($franchiseInitial, (float) $this->rasrajFranchise->fresh()->wallet_balance);
    }

    /**
     * If Super Admin wallet balance is insufficient for base gateway cost, request is rejected.
     */
    public function test_insufficient_super_admin_balance_rejects_service_execution(): void
    {
        // Set Super Admin wallet balance to 1.00 token (less than 4.00 required)
        $this->superAdmin->wallet_balance = 1.00;
        $this->superAdmin->save();

        $response = $this->withHeaders([
            'X-API-KEY' => $this->rasrajFranchise->api_key,
        ])->postJson(route('api.service.execute', 'dl_verification_plus_srv2'), [
            'dlNumber' => 'DL1420110012345',
            'dob' => '1990-01-01',
        ]);

        $response->assertStatus(402);
        $response->assertJson([
            'status' => 'FAILED',
            'response_code' => '402',
            'message' => 'System gateway processing balance is insufficient. Please contact administration.',
        ]);

        // Neither balance was deducted
        $this->assertEquals(500.00, (float) $this->rasrajFranchise->fresh()->wallet_balance);
        $this->assertEquals(1.00, (float) $this->superAdmin->fresh()->wallet_balance);
    }

    /**
     * If IDSPay upstream fails, zero tokens are deducted from both franchise and Super Admin.
     */
    public function test_upstream_failure_does_not_deduct_tokens_from_franchise_or_super_admin(): void
    {
        FranchiseServicePrice::create([
            'user_id' => $this->rasrajFranchise->id,
            'service_id' => $this->dlService->id,
            'price' => 30.00,
        ]);

        $mockIdspay = Mockery::mock(IdspayClient::class);
        $mockIdspay->shouldReceive('dispatch')
            ->once()
            ->andReturn([
                'success' => false,
                'status' => 'FAILED',
                'http_code' => 500,
                'error' => 'Upstream gateway down',
                'execution_time_ms' => 300,
            ]);

        $this->app->instance(IdspayClient::class, $mockIdspay);

        $response = $this->withHeaders([
            'X-API-KEY' => $this->rasrajFranchise->api_key,
        ])->postJson(route('api.service.execute', 'dl_verification_plus_srv2'), [
            'dlNumber' => 'DL1420110012345',
            'dob' => '1990-01-01',
        ]);

        $response->assertStatus(500);

        // Zero deductions
        $this->assertEquals(500.00, (float) $this->rasrajFranchise->fresh()->wallet_balance);
        $this->assertEquals(10000.00, (float) $this->superAdmin->fresh()->wallet_balance);
    }
}
