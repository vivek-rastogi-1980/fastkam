<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Rajesh Sharma',
            'email' => 'rajesh@example.com',
            'phone' => '9876543210',
            'password' => Hash::make('oldPassword123'),
            'plain_password' => 'oldPassword123',
            'role' => 'franchise',
            'status' => 'active',
        ]);
    }

    public function test_login_page_displays_forgot_password_link(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee(route('password.request'));
        $response->assertSee('Forgot Password?');
    }

    public function test_forgot_password_page_can_be_rendered(): void
    {
        $response = $this->get(route('password.request'));

        $response->assertStatus(200);
        $response->assertSee('Forgot Password?');
        $response->assertSee('SEND RESET LINK');
    }

    public function test_unknown_email_returns_error(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'unknown@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'unknown@example.com',
        ]);
    }

    public function test_can_request_reset_link_using_registered_email(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'rajesh@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
        $response->assertSessionHas('reset_url');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'rajesh@example.com',
        ]);
    }

    public function test_can_request_reset_link_using_registered_phone(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => '9876543210',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
        $response->assertSessionHas('reset_url');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'rajesh@example.com',
        ]);
    }

    public function test_reset_password_page_can_be_rendered_with_token(): void
    {
        $response = $this->get(route('password.reset', [
            'token' => 'sample-test-token',
            'email' => 'rajesh@example.com',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Create New Password');
        $response->assertSee('rajesh@example.com');
        $response->assertSee('RESET PASSWORD');
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        $rawToken = 'valid-secure-random-token-12345';

        DB::table('password_reset_tokens')->insert([
            'email' => 'rajesh@example.com',
            'token' => Hash::make($rawToken),
            'created_at' => now(),
        ]);

        $response = $this->post(route('password.update'), [
            'token' => $rawToken,
            'email' => 'rajesh@example.com',
            'password' => 'newSecretPass999',
            'password_confirmation' => 'newSecretPass999',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        // Check password updated
        $this->user->refresh();
        $this->assertTrue(Hash::check('newSecretPass999', $this->user->password));
        $this->assertEquals('newSecretPass999', $this->user->plain_password);

        // Check token removed
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'rajesh@example.com',
        ]);
    }

    public function test_password_reset_fails_with_invalid_token(): void
    {
        DB::table('password_reset_tokens')->insert([
            'email' => 'rajesh@example.com',
            'token' => Hash::make('real-token'),
            'created_at' => now(),
        ]);

        $response = $this->post(route('password.update'), [
            'token' => 'wrong-token',
            'email' => 'rajesh@example.com',
            'password' => 'newSecretPass999',
            'password_confirmation' => 'newSecretPass999',
        ]);

        $response->assertSessionHasErrors(['email']);

        // Password should remain unchanged
        $this->user->refresh();
        $this->assertTrue(Hash::check('oldPassword123', $this->user->password));
        $this->assertEquals('oldPassword123', $this->user->plain_password);
    }
}
