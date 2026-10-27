<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WalletTransaction;
use App\Services\RechPayChecksum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class WalletRechargeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_add_balance_page(): void
    {
        $response = $this->get(route('wallet.add_balance'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_add_balance_page(): void
    {
        $user = User::factory()->create([
            'role' => 'franchise',
            'wallet_balance' => 250.00,
        ]);

        $response = $this->actingAs($user)->get(route('wallet.add_balance'));

        $response->assertStatus(200);
        $response->assertSee('Add Wallet Balance');
        $response->assertSee('CURRENT WALLET BALANCE');
        $response->assertSee('250.00');
        $response->assertSee('Recharge Wallet');
        $response->assertSee('QUICK SELECT AMOUNT');
        $response->assertSee('₹100');
        $response->assertSee('₹200');
        $response->assertSee('₹500');
        $response->assertSee('₹1,000');
        $response->assertSee('₹2,000');
        $response->assertSee('₹5,000');
        $response->assertSee('OR ENTER CUSTOM AMOUNT');
        $response->assertSee('Proceed to Pay');
    }

    public function test_services_page_displays_add_token_buttons(): void
    {
        $user = User::factory()->create([
            'role' => 'franchise',
            'wallet_balance' => 100.00,
        ]);

        $response = $this->actingAs($user)->get(route('admin.services'));

        $response->assertStatus(200);
        $response->assertSee('Add Token');
        $response->assertSee(route('wallet.add_balance'));
    }

    public function test_process_payment_validates_minimum_amount(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('wallet.process_payment'), [
            'amount' => 50,
        ]);

        $response->assertSessionHasErrors(['amount']);
    }

    public function test_process_payment_renders_redirect_with_apnacsc_parameters(): void
    {
        config(['services.apnacsc.test_mode' => false]);

        $user = User::factory()->create([
            'phone' => '9876543210',
            'email' => 'testuser@example.com',
        ]);

        $response = $this->actingAs($user)->post(route('wallet.process_payment'), [
            'amount' => 500,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('wallet.redirect');
        $response->assertSee('Connecting to ApnaCSC');
        $response->assertSee('54847'); // upiuid
        $response->assertSee(config('services.apnacsc.token')); // token
        $response->assertSee('500'); // txnAmount
        $response->assertSee('WalletRecharge');
        $response->assertSee('CHECKSUMHASH');
        $response->assertSee('checksum');
    }

    public function test_test_mode_process_payment_credits_wallet_immediately(): void
    {
        config(['services.apnacsc.test_mode' => true]);

        $user = User::factory()->create([
            'wallet_balance' => 200.00,
        ]);

        $response = $this->actingAs($user)->post(route('wallet.process_payment'), [
            'amount' => 150.00,
        ]);

        $response->assertRedirect();
        $targetUrl = $response->headers->get('Location');

        // Follow the redirect to callback
        $callbackResponse = $this->actingAs($user)->get($targetUrl);
        $callbackResponse->assertStatus(200);
        $callbackResponse->assertViewIs('wallet.payment_success');

        $user->refresh();
        $this->assertEquals(350.00, (float) $user->wallet_balance);
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $user->id,
            'type' => 'credit',
            'amount' => 150.00,
        ]);
    }

    public function test_successful_callback_credits_wallet_balance_and_creates_transaction(): void
    {
        $user = User::factory()->create([
            'wallet_balance' => 100.00,
        ]);

        $orderId = 'ORDS12345UID' . $user->id . 'AMT300R999';
        Cache::put('apnacsc_order_' . $orderId, [
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => 300.00,
        ], now()->addHours(1));

        $response = $this->post(route('wallet.payment.callback'), [
            'orderId' => $orderId,
            'status' => 'SUCCESS',
            'message' => 'Payment Approved',
            'txnId' => 'TXN_APNA_TEST_99',
            'txnAmount' => 300.00,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('wallet.payment_success');

        $user->refresh();
        $this->assertEquals(400.00, (float) $user->wallet_balance);

        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $user->id,
            'reference_id' => $orderId,
            'type' => 'credit',
            'amount' => 300.00,
        ]);
    }

    public function test_callback_extracts_user_and_amount_from_order_id_without_cache(): void
    {
        $user = User::factory()->create([
            'wallet_balance' => 50.00,
        ]);

        $orderId = 'ORDS' . time() . 'UID' . $user->id . 'AMT250R789';

        // Note: No Cache::put and unauthenticated (simulating cross-site gateway webhook)
        $response = $this->post(route('wallet.payment.callback'), [
            'orderId' => $orderId,
            'status' => 'SUCCESS',
            'message' => 'Payment Approved',
            'txnId' => 'TXN_WEBHOOK_01',
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('wallet.payment_success');

        $user->refresh();
        $this->assertEquals(300.00, (float) $user->wallet_balance);
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $user->id,
            'reference_id' => $orderId,
            'type' => 'credit',
            'amount' => 250.00,
        ]);
    }

    public function test_failed_callback_does_not_credit_wallet(): void
    {
        $user = User::factory()->create([
            'wallet_balance' => 100.00,
        ]);

        $orderId = 'ORDS12345UID' . $user->id . 'AMT300R999';
        Cache::put('apnacsc_order_' . $orderId, [
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => 300.00,
        ], now()->addHours(1));

        $response = $this->post(route('wallet.payment.callback'), [
            'orderId' => $orderId,
            'status' => 'FAILED',
            'message' => 'Unauthorized Access or Token Is Invalid',
            'txnId' => 'TXN_FAIL_01',
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('wallet.payment_failed');

        $user->refresh();
        $this->assertEquals(100.00, (float) $user->wallet_balance);
    }

    public function test_tampered_amount_in_callback_is_locked_to_authentic_order_amount(): void
    {
        $user = User::factory()->create([
            'wallet_balance' => 100.00,
        ]);

        $orderId = 'ORDS' . time() . 'UID' . $user->id . 'AMT100R111';
        Cache::put('apnacsc_order_' . $orderId, [
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => 100.00, // Authentic initiated amount
        ], now()->addHours(1));

        // An attacker/tampered payload sends txnAmount = 5000
        $response = $this->post(route('wallet.payment.callback'), [
            'orderId' => $orderId,
            'status' => 'SUCCESS',
            'message' => 'Payment Approved',
            'txnId' => 'TXN_TAMPER_TEST',
            'txnAmount' => 5000.00,
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('wallet.payment_success');

        $user->refresh();
        // Wallet must ONLY be credited with the authentic 100.00, not 5000.00
        $this->assertEquals(200.00, (float) $user->wallet_balance);
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $user->id,
            'reference_id' => $orderId,
            'type' => 'credit',
            'amount' => 100.00,
        ]);
    }

    public function test_simulate_success_is_forbidden_in_production(): void
    {
        $this->app->detectEnvironment(fn() => 'production');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('wallet.simulate_success', [
            'orderId' => 'ORDS_TEST_123',
            'amount' => 100,
        ]));

        $response->assertStatus(403);
    }

    public function test_callback_with_valid_rechpay_signature_is_accepted(): void
    {
        $user = User::factory()->create([
            'wallet_balance' => 150.00,
        ]);

        $orderId = 'ORDS' . time() . 'UID' . $user->id . 'AMT200R123';
        Cache::put('apnacsc_order_' . $orderId, [
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => 200.00,
        ], now()->addHours(1));

        $secret = 'DRocpf8V0Y';
        config(['services.apnacsc.secret' => $secret]);

        $payload = [
            'orderId' => $orderId,
            'status' => 'SUCCESS',
            'message' => 'Payment Approved',
            'txnId' => 'TXN_RECHPAY_VAL_01',
            'txnAmount' => '200.00',
        ];

        $signature = RechPayChecksum::generateSignature($payload, $secret);
        $payload['CHECKSUMHASH'] = $signature;

        $response = $this->post(route('wallet.payment.callback'), $payload);

        $response->assertStatus(200);
        $response->assertViewIs('wallet.payment_success');

        $user->refresh();
        $this->assertEquals(350.00, (float) $user->wallet_balance);
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $user->id,
            'reference_id' => $orderId,
            'amount' => 200.00,
        ]);
    }

    public function test_callback_with_tampered_signature_is_rejected(): void
    {
        $user = User::factory()->create([
            'wallet_balance' => 100.00,
        ]);

        $orderId = 'ORDS' . time() . 'UID' . $user->id . 'AMT500R456';
        Cache::put('apnacsc_order_' . $orderId, [
            'user_id' => $user->id,
            'order_id' => $orderId,
            'amount' => 500.00,
        ], now()->addHours(1));

        $secret = 'DRocpf8V0Y';
        config(['services.apnacsc.secret' => $secret]);

        $payload = [
            'orderId' => $orderId,
            'status' => 'SUCCESS',
            'message' => 'Payment Approved',
            'txnId' => 'TXN_RECHPAY_TAMPER',
            'txnAmount' => '500.00',
            // Invalid / forged signature
            'CHECKSUMHASH' => 'invalid_tampered_signature_string==',
        ];

        $response = $this->post(route('wallet.payment.callback'), $payload);

        $response->assertStatus(200);
        $response->assertViewIs('wallet.payment_failed');
        $response->assertSee('Security Checksum Verification Failed');

        $user->refresh();
        // Wallet must NOT be credited
        $this->assertEquals(100.00, (float) $user->wallet_balance);
        $this->assertDatabaseMissing('wallet_transactions', [
            'reference_id' => $orderId,
        ]);
    }
}
