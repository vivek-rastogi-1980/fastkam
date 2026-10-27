<?php

namespace Tests\Feature;

use App\Models\ApiLog;
use App\Models\Service;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ApiServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $franchise;
    protected Service $panService;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'idspay.mock_mode' => false,
            'idspay.api_key' => 'test_api_key_123',
            'idspay.base_url' => 'https://apiuser.idspay.in',
        ]);

        // Create a franchise user with 100.00 Tokens
        $this->franchise = User::create([
            'name' => 'Test Franchise',
            'email' => 'franchise@test.com',
            'password' => Hash::make('password123'),
            'role' => 'franchise',
            'phone' => '9876543210',
            'address' => 'Test Address, City',
            'dob' => '1995-05-10',
            'gov_id_type' => 'aadhaar',
            'gov_id_number' => '123456789012',
            'api_key' => 'vle_test_api_key_1234567890',
            'api_secret' => 'secret_token_123',
            'wallet_balance' => 100.00,
            'status' => 'active',
        ]);

        // Create PAN service costing 5.00 tokens
        $this->panService = Service::create([
            'name' => 'PAN via Aadhaar Lookup',
            'service_code' => 'pan_aadhaar',
            'category' => 'Identity & Tax',
            'description' => 'Verify PAN using Aadhaar number.',
            'endpoint_path' => '/api/v1/pan/aadhaar-lookup',
            'http_method' => 'POST',
            'token_cost' => 5.00,
            'is_active' => true,
            'sample_payload' => ['aadhaar_number' => '123456789012'],
            'required_fields' => ['aadhaar_number'],
        ]);
    }

    /**
     * Test API authentication fails without API Key.
     */
    public function test_api_requires_valid_api_key(): void
    {
        $response = $this->postJson('/api/v1/service/pan-aadhaar', [
            'aadhaar_number' => '123456789012',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'FAILED',
                'response_code' => '401',
            ]);
    }

    /**
     * Test successful service execution deducts tokens and creates wallet + audit logs.
     */
    public function test_service_executes_successfully_and_deducts_tokens(): void
    {
        Http::fake([
            'https://apiuser.idspay.in/*' => Http::response([
                'status' => 'SUCCESS',
                'response_code' => '00',
                'message' => 'PAN details retrieved successfully',
                'transaction_id' => 'IDSPAY_TXN_99999',
                'pan_number' => 'ABCDE1234F',
            ], 200),
        ]);

        $initialBalance = (float) $this->franchise->wallet_balance;

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchise->api_key,
        ])->postJson('/api/v1/service/pan-aadhaar', [
            'aadhaar_number' => '123456789012',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'response_code' => '200',
                'billing' => [
                    'tokens_deducted' => 5.00,
                    'remaining_balance' => 95.00,
                ],
            ]);

        // Check wallet balance was deducted
        $this->franchise->refresh();
        $this->assertEquals(95.00, (float) $this->franchise->wallet_balance);

        // Check Wallet Transaction was recorded
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $this->franchise->id,
            'type' => 'debit',
            'amount' => 5.00,
            'opening_balance' => 100.00,
            'closing_balance' => 95.00,
        ]);

        // Check API Log was saved
        $this->assertDatabaseHas('api_logs', [
            'user_id' => $this->franchise->id,
            'service_code' => 'pan_aadhaar',
            'tokens_deducted' => 5.00,
            'status' => 'SUCCESS',
        ]);
    }

    /**
     * Test when IDSPay fails or times out: DO NOT deduct tokens.
     */
    public function test_service_failure_does_not_deduct_tokens(): void
    {
        Http::fake([
            'https://apiuser.idspay.in/*' => Http::response([
                'status' => 'FAILED',
                'message' => 'Aadhaar not linked with any PAN record',
            ], 400),
        ]);

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchise->api_key,
        ])->postJson('/api/v1/service/pan-aadhaar', [
            'aadhaar_number' => '123456789012',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'FAILED',
                'tokens_deducted' => 0.00,
            ]);

        // Balance should remain unchanged!
        $this->franchise->refresh();
        $this->assertEquals(100.00, (float) $this->franchise->wallet_balance);

        // Wallet transactions should NOT have a debit record
        $this->assertEquals(0, WalletTransaction::where('user_id', $this->franchise->id)->count());

        // ApiLog should record the attempt as FAILED
        $this->assertDatabaseHas('api_logs', [
            'user_id' => $this->franchise->id,
            'service_code' => 'pan_aadhaar',
            'tokens_deducted' => 0.00,
            'status' => 'FAILED',
        ]);
    }

    /**
     * Test insufficient wallet balance triggers 402 Payment Required.
     */
    public function test_insufficient_tokens_returns_402_and_no_deduction(): void
    {
        // Set balance lower than 5.00 token cost
        $this->franchise->wallet_balance = 2.00;
        $this->franchise->save();

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchise->api_key,
        ])->postJson('/api/v1/service/pan-aadhaar', [
            'aadhaar_number' => '123456789012',
        ]);

        $response->assertStatus(402)
            ->assertJson([
                'status' => 'FAILED',
                'response_code' => '402',
            ]);

        $this->franchise->refresh();
        $this->assertEquals(2.00, (float) $this->franchise->wallet_balance);

        // ApiLog should record INSUFFICIENT_FUNDS
        $this->assertDatabaseHas('api_logs', [
            'user_id' => $this->franchise->id,
            'service_code' => 'pan_aadhaar',
            'tokens_deducted' => 0.00,
            'status' => 'INSUFFICIENT_FUNDS',
        ]);
    }

    /**
     * Test password update validation and change.
     */
    public function test_user_can_update_password(): void
    {
        // 1. GET /password/edit should prefill current password and hide requirements checklist
        $editResponse = $this->actingAs($this->franchise)->get('/password/edit');
        $editResponse->assertStatus(200);
        $editResponse->assertDontSee('Password Requirements:');
        $editResponse->assertDontSee('Minimum 8 characters');
        $editResponse->assertSee('value="password123"', false);

        // 2. Submit password change
        $response = $this->actingAs($this->franchise)
            ->post('/password/update', [
                'current_password' => 'password123',
                'password' => 'NewStrongPassword!23',
                'password_confirmation' => 'NewStrongPassword!23',
            ]);

        $response->assertRedirect('/password/edit');
        $this->franchise->refresh();

        $this->assertTrue(Hash::check('NewStrongPassword!23', $this->franchise->password));
        $this->assertEquals('NewStrongPassword!23', $this->franchise->plain_password);

        // 3. Next visit to edit page should prefill newly updated password
        $nextEditResponse = $this->actingAs($this->franchise)->get('/password/edit');
        $nextEditResponse->assertSee('value="NewStrongPassword!23"', false);
    }

    /**
     * Test that newly added IDSPay services execute with essential inputs.
     */
    public function test_new_services_execute_with_essential_inputs(): void
    {
        $dlService = Service::create([
            'name' => 'DL Verification Plus (Srv2)',
            'service_code' => 'dl_verification_plus_srv2',
            'category' => 'Transport & Driving',
            'description' => 'Verify driving license details',
            'endpoint_path' => '/api/v1/dl/verification-plus',
            'http_method' => 'POST',
            'token_cost' => 3.50,
            'is_active' => true,
            'sample_payload' => ['dlNumber' => 'DL1420110012345', 'dob' => '1990-05-15'],
            'required_fields' => ['dlNumber', 'dob'],
        ]);

        Http::fake([
            'https://apiuser.idspay.in/*' => Http::response([
                'status' => 'SUCCESS',
                'response_code' => '00',
                'message' => 'DL details retrieved',
                'transaction_id' => 'TXN_DL_123',
            ], 200),
        ]);

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchise->api_key,
        ])->postJson('/api/v1/service/dl-verification-plus', [
            'dlNumber' => 'DL1420110012345',
            'dob' => '1990-05-15',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'billing' => [
                    'tokens_deducted' => 3.50,
                ],
            ]);
    }

    /**
     * Test DL Verification Plus (Srv2) with exact credentials and endpoint structure.
     */
    public function test_dl_verification_plus_srv2_with_live_endpoint_and_credentials(): void
    {
        $dlService = Service::create([
            'name' => 'DL Verification Plus (Srv2)',
            'service_code' => 'dl_verification_plus_srv2_test',
            'category' => 'Transport & Vahan',
            'description' => 'Verify driving license details',
            'endpoint_path' => 'https://javabackend.idspay.in/api/v1/uat/srv2/validation/dl-plus',
            'http_method' => 'POST',
            'token_cost' => 3.00,
            'is_active' => true,
            'sample_payload' => [
                'api_id' => 'APID3597',
                'api_key' => '679d36c9-1897-41ca-aabb-8549842c33c7',
                'token_id' => 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN',
                'dlNumber' => 'DL-1420110012345',
                'dob' => '1990-05-15',
            ],
            'required_fields' => ['dlNumber', 'dob'],
        ]);

        config([
            'idspay.api_key' => '679d36c9-1897-41ca-aabb-8549842c33c7',
            'idspay.api_id' => 'APID3597',
            'idspay.token_id' => 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN',
        ]);
        $this->app->forgetInstance(\App\Services\IdspayClient::class);

        Http::fake([
            'https://javabackend.idspay.in/api/v1/uat/srv2/validation/dl-plus' => function ($request) {
                $body = $request->data();
                $this->assertEquals('APID3597', $body['api_id']);
                $this->assertEquals('679d36c9-1897-41ca-aabb-8549842c33c7', $body['api_key']);
                $this->assertEquals('ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN', $body['token_id']);
                $this->assertEquals('DL-1420110012345', $body['dlNumber']);
                $this->assertEquals('1990-05-15', $body['dob']);

                return Http::response([
                    'status' => [
                        'code' => 200,
                        'type' => 'success',
                        'message' => 'DL Verification Plus (Srv2) response processed successfully.',
                    ],
                    'message' => 'DL Verification Plus (Srv2) response processed successfully.',
                    'data' => [
                        'dl_number' => 'TN2XXXXX502',
                        'dob' => '13-07-1991',
                        'details_of_driving_licence' => [
                            'name' => 'AMXXXXXJ R',
                            'status' => 'Active',
                            'expiry_date' => '24-09-2036',
                        ],
                    ],
                ], 200);
            },
        ]);

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchise->api_key,
        ])->postJson(route('api.service.execute', 'dl_verification_plus_srv2_test'), [
            'dlNumber' => 'DL-1420110012345',
            'dob' => '1990-05-15',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'response_code' => '200',
                'service' => [
                    'code' => 'dl_verification_plus_srv2_test',
                ],
            ]);
    }

    /**
     * Test admin can create a new franchise with full name and password confirmation.
     */
    public function test_admin_can_create_franchise_with_full_name_and_password_confirmation(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin_test@test.com',
            'password' => Hash::make('adminpassword'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post('/franchises', [
            'name' => 'Om Digital Seva Kendra',
            'full_name' => 'Rajesh Kumar Sharma',
            'email' => 'rajesh.om@example.com',
            'password' => 'SecurePass123!',
            'password_confirmation' => 'SecurePass123!',
            'phone' => '9876543210',
            'address' => 'Shop 12, Main Bazaar, Delhi',
            'dob' => '1988-06-15',
            'gov_id_type' => 'pan',
            'gov_id_number' => 'ABCDE1234F',
            'initial_tokens' => 250.00,
        ]);

        $response->assertRedirect('/franchises');
        $this->assertDatabaseHas('users', [
            'name' => 'Om Digital Seva Kendra',
            'full_name' => 'Rajesh Kumar Sharma',
            'email' => 'rajesh.om@example.com',
            'role' => 'franchise',
            'wallet_balance' => 250.00,
        ]);

        $created = User::where('email', 'rajesh.om@example.com')->first();
        $this->assertNotNull($created);
        $this->assertTrue(Hash::check('SecurePass123!', $created->password));
    }

    /**
     * Test franchise creation fails when password confirmation does not match.
     */
    public function test_create_franchise_fails_when_password_confirmation_mismatched(): void
    {
        $admin = User::create([
            'name' => 'Admin User 2',
            'email' => 'admin_test2@test.com',
            'password' => Hash::make('adminpassword'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post('/franchises', [
            'name' => 'Mismatched Password Kendra',
            'full_name' => 'Test Operator',
            'email' => 'mismatch@example.com',
            'password' => 'PasswordOne123',
            'password_confirmation' => 'DifferentPassword456',
            'phone' => '9876543210',
            'address' => 'Test Address',
            'dob' => '1990-01-01',
            'gov_id_type' => 'aadhaar',
            'gov_id_number' => '123412341234',
        ]);

        $response->assertSessionHasErrors(['password']);
        $this->assertDatabaseMissing('users', [
            'email' => 'mismatch@example.com',
        ]);
    }

    /**
     * Test franchise dashboard hides TOTAL FRANCHISES and scopes API CALLS TODAY to own user.
     */
    public function test_franchise_dashboard_hides_total_franchises_and_scopes_api_calls(): void
    {
        // Another franchise
        $otherUser = User::create([
            'name' => 'Other Franchise',
            'email' => 'other@test.com',
            'password' => Hash::make('password123'),
            'role' => 'franchise',
        ]);

        // Create log for other user today
        ApiLog::create([
            'user_id' => $otherUser->id,
            'service_id' => $this->panService->id,
            'service_code' => 'pan_aadhaar',
            'tokens_deducted' => 5.00,
            'status' => 'SUCCESS',
            'created_at' => now(),
        ]);

        // Create log for our franchise today
        ApiLog::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'service_code' => 'pan_aadhaar',
            'tokens_deducted' => 5.00,
            'status' => 'SUCCESS',
            'created_at' => now(),
        ]);

        $response = $this->actingAs($this->franchise)->get('/services');

        $response->assertStatus(200);
        $response->assertDontSee('TOTAL FRANCHISES');
        $response->assertSee('MY VERIFICATIONS TODAY');
        $response->assertSee(route('admin.logs', ['today' => 1]));

        // Franchise accessing logs sees only their own log with Service Name
        $logsResponse = $this->actingAs($this->franchise)->get('/logs');
        $logsResponse->assertStatus(200);
        $logsResponse->assertSee('Service Name');
        $logsResponse->assertSee($this->panService->name);
        $logsResponse->assertSee('UID: ' . $this->franchise->id);
        $logsResponse->assertDontSee('UID: ' . $otherUser->id);
    }

    /**
     * Test date range filtering on API logs with both ISO and friendly date strings.
     */
    public function test_logs_date_range_filtering(): void
    {
        // Out of range (earlier)
        ApiLog::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'service_code' => 'pan_aadhaar',
            'tokens_deducted' => 5.00,
            'status' => 'SUCCESS',
            'created_at' => \Carbon\Carbon::parse('2026-11-01 10:00:00'),
        ]);

        // Inside range
        $targetLog = ApiLog::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'service_code' => 'pan_aadhaar',
            'tokens_deducted' => 5.00,
            'status' => 'SUCCESS',
            'ip_address' => '10.20.30.40',
            'created_at' => \Carbon\Carbon::parse('2026-11-03 14:30:00'),
        ]);

        // Out of range (later)
        ApiLog::create([
            'user_id' => $this->franchise->id,
            'service_id' => $this->panService->id,
            'service_code' => 'pan_aadhaar',
            'tokens_deducted' => 5.00,
            'status' => 'SUCCESS',
            'created_at' => \Carbon\Carbon::parse('2026-11-10 16:00:00'),
        ]);

        // Test with friendly date format like "2nd Nov 26 to 5 Nov 26"
        $respFriendly = $this->actingAs($this->franchise)->get('/logs?date_range=' . urlencode('2nd Nov 26 to 5 Nov 26'));
        $respFriendly->assertStatus(200);
        $respFriendly->assertSee('10.20.30.40');
        $respFriendly->assertSee('3rd Nov 2026');
        $respFriendly->assertSee('2nd Nov 26 to 5th Nov 26');

        // Test with ISO date format "2026-11-02 to 2026-11-05"
        $respIso = $this->actingAs($this->franchise)->get('/logs?date_range=' . urlencode('2026-11-02 to 2026-11-05'));
        $respIso->assertStatus(200);
        $respIso->assertSee('10.20.30.40');
        $respIso->assertSee('3rd Nov 2026');

        // Test with from_date & to_date parameters
        $respFromTo = $this->actingAs($this->franchise)->get('/logs?from_date=2026-11-02&to_date=2026-11-05');
        $respFromTo->assertStatus(200);
        $respFromTo->assertSee('10.20.30.40');
    }

    /**
     * Test that the Services page renders the Driving License card with Front & Back sides
     * and the Verified Record Details section is hidden.
     */
    public function test_driving_licence_card_renders_front_and_back_elements_and_hides_verified_record_details(): void
    {
        $response = $this->actingAs($this->franchise)->get('/services');

        $response->assertStatus(200);

        // Front Card Elements
        $response->assertSee('dlCardsContainer');
        $response->assertSee('dlFrontCol');
        $response->assertSee('displayDlNumber');
        $response->assertSee('displayDlDob');
        $response->assertSee('displayDlDateOfIssue');
        $response->assertSee('displayDlStatusBadge');
        $response->assertSee('displayDlHolderName');
        $response->assertSee('displayDlRelationName');
        $response->assertSee('displayDlFrontBloodGroup');
        $response->assertSee('displayDlPhoto');
        $response->assertSee('displayDlExpiryDate');

        // Back Card Elements
        $response->assertSee('dlBackCol');
        $response->assertSee('displayDlCompleteAddress');
        $response->assertSee('displayDlCovBadge');
        $response->assertSee('displayDlVehicleCategoryTbody');
        $response->assertSee('displayDlBackBloodGroup');

        // View Switcher & Action controls
        $response->assertSee('dlViewBothBtn');
        $response->assertSee('dlViewFrontBtn');
        $response->assertSee('dlViewBackBtn');
        $response->assertSee('switchDlCardView');
        $response->assertSee('printDlSmartCard');

        // Verified Record Details section should have d-none
        $response->assertSee('id="allSuccessDataCard" class="card border-0 shadow-sm rounded-3 overflow-hidden d-none mb-3"', false);
    }

    /**
     * Test that the Services page renders the complete e-PAN card with all response attributes
     * for Pan details V1 (pan, pan_type, fullname, split names, father_name, gender,
     * aadhaar_number, aadhaar_linked, dob, address, mobile, email).
     */
    public function test_pan_card_renders_all_fields_under_pan_details_v1_and_hides_verified_record_details(): void
    {
        $response = $this->actingAs($this->franchise)->get('/services');

        $response->assertStatus(200);

        // e-PAN Card Structure & Printable Area
        $response->assertSee('id="panSuccessCard"', false);
        $response->assertSee('id="panCardPrintArea"', false);
        $response->assertSee('pan-id-card');

        // All 14 Core Identity & Demographic Attributes
        $response->assertSee('id="displayPanNumber"', false);
        $response->assertSee('id="displayPanType"', false);
        $response->assertSee('id="displayPanFullName"', false);
        $response->assertSee('id="displayPanFirstName"', false);
        $response->assertSee('id="displayPanMiddleName"', false);
        $response->assertSee('id="displayPanLastName"', false);
        $response->assertSee('id="displayPanFatherName"', false);
        $response->assertSee('id="displayPanGender"', false);
        $response->assertSee('id="displayPanAadhaarNumber"', false);
        $response->assertSee('id="displayPanAadhaarLinkedBadge"', false);
        $response->assertSee('id="displayPanDob"', false);
        $response->assertSee('id="displayPanAddress"', false);
        $response->assertSee('id="displayPanMobile"', false);
        $response->assertSee('id="displayPanEmail"', false);

        // Action Toolbar & Print / Copy helpers
        $response->assertSee('id="btnCopyPanResult"', false);
        $response->assertSee('id="btnCopyPanFullResult"', false);
        $response->assertSee('id="btnPrintPanCard"', false);
        $response->assertSee('copyPanFullDetails');
        $response->assertSee('printPanSmartCard');

        // Verified Record Details section should remain hidden
        $response->assertSee('id="allSuccessDataCard" class="card border-0 shadow-sm rounded-3 overflow-hidden d-none mb-3"', false);
    }

    /**
     * Test that the Services page renders the official Aadhaar to PAN Linkage Certificate Card
     * with dual authority header, input Aadhaar, unmasked linked PAN, tax category badge,
     * toolbar copy/print buttons, and keeps Verified Record Details hidden.
     */
    public function test_aadhar_to_pan_card_renders_with_dual_linkage_and_hides_verified_record_details(): void
    {
        $response = $this->actingAs($this->franchise)->get('/services');

        $response->assertStatus(200);

        // Aadhaar To Pan Linkage Card & Print Area
        $response->assertSee('id="aadharToPanSuccessCard"', false);
        $response->assertSee('id="aadharPanCardPrintArea"', false);
        $response->assertSee('aadhar-pan-card');

        // Official Dual Authorities & Compliance
        $response->assertSee('GOVT. OF INDIA / UIDAI & INCOME TAX DEPARTMENT', false);
        $response->assertSee('SECTION 139AA COMPLIANT');

        // Key Card Data Attributes
        $response->assertSee('id="displayAadharInputNumber"', false);
        $response->assertSee('id="displayAadharMaskedNumber"', false);
        $response->assertSee('id="displayAadharLinkedPan"', false);
        $response->assertSee('id="displayAadharPanTypeBadge"', false);
        $response->assertSee('id="displayAadharPanStatusBadge"', false);
        $response->assertSee('id="displayAadharPanMessage"', false);
        $response->assertSee('id="displayAadharPanFee"', false);

        // Action Toolbar Buttons & Helpers
        $response->assertSee('id="btnCopyAadharPanResult"', false);
        $response->assertSee('id="btnCopyAadharPanFullResult"', false);
        $response->assertSee('id="btnPrintAadharPanCard"', false);
        $response->assertSee('copyAadharPanFullDetails');
        $response->assertSee('printAadharPanSmartCard');

        // Verified Record Details section should remain hidden
        $response->assertSee('id="allSuccessDataCard" class="card border-0 shadow-sm rounded-3 overflow-hidden d-none mb-3"', false);
    }

    /**
     * Test successful execution of Aadhar To Pan service with UAT endpoint and credentials.
     */
    public function test_aadhar_to_pan_service_executes_successfully_and_returns_unmasked_pan(): void
    {
        $service = Service::create([
            'name' => 'Aadhar To Pan',
            'service_code' => 'aadhar_to_pan',
            'category' => 'Identity Verification',
            'description' => 'Retrieve unmasked linked PAN from 12-digit Aadhaar number.',
            'endpoint_path' => 'https://javabackend.idspay.in/api/v1/uat/srv2/validation/aadhaar-to-unmasked-pan',
            'http_method' => 'POST',
            'token_cost' => 5.00,
            'is_active' => true,
            'sample_payload' => [
                'api_id' => 'APID3597',
                'api_key' => '679d36c9-1897-41ca-aabb-8549842c33c7',
                'token_id' => 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN',
                'aadhar' => '521234567897',
            ],
            'required_fields' => ['aadhar'],
        ]);

        Http::fake([
            'https://javabackend.idspay.in/api/v1/uat/srv2/validation/aadhaar-to-unmasked-pan' => Http::response([
                'status' => [
                    'code' => 200,
                    'type' => 'success',
                    'message' => 'Aadhar To Pan response processed successfully.',
                ],
                'message' => 'Aadhar To Pan response processed successfully.',
                'data' => [
                    'linked_pan' => 'GENPB6869D',
                ],
            ], 200),
        ]);

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchise->api_key,
        ])->postJson('/api/v1/service/execute/aadhar_to_pan', [
            'aadhar' => '521234567897',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'service' => [
                    'code' => 'aadhar_to_pan',
                    'name' => 'Aadhar To Pan',
                ],
                'pan_number' => 'GENPB6869D',
            ]);

        $this->assertEquals(95.00, $this->franchise->fresh()->wallet_balance);
        $this->assertDatabaseHas('api_logs', [
            'service_id' => $service->id,
            'status' => 'SUCCESS',
        ]);
    }

    /**
     * Test that the Services page renders the official Vehicle RC To Mobile Linkage Certificate Card
     * with MoRTH header, input plate, registered mobile display, copy/print toolbar,
     * and keeps Verified Record Details hidden.
     */
    public function test_rc_to_mobile_card_renders_with_dual_linkage_and_hides_verified_record_details(): void
    {
        $response = $this->actingAs($this->franchise)->get('/services');

        $response->assertStatus(200);

        // RC To Mobile Linkage Card & Printable Area
        $response->assertSee('id="rcSuccessCard"', false);
        $response->assertSee('id="rcCardPrintArea"', false);
        $response->assertSee('rc-smart-card');

        // Official Dual Authorities & Compliance
        $response->assertSee('GOVT. OF INDIA / MINISTRY OF ROAD TRANSPORT &amp; HIGHWAYS', false);
        $response->assertSee('CMVR FORM 23 COMPLIANT');

        // Key Card Data Attributes
        $response->assertSee('id="displayRcPlate"', false);
        $response->assertSee('id="displayRcMobile"', false);
        $response->assertSee('id="displayRcOwnerName"', false);
        $response->assertSee('id="displayRcModel"', false);
        $response->assertSee('id="displayRcFuel"', false);
        $response->assertSee('id="displayRcRegDate"', false);
        $response->assertSee('id="displayRcStatusBadge"', false);
        $response->assertSee('id="displayRcMessage"', false);
        $response->assertSee('id="displayRcFee"', false);

        // Action Toolbar Buttons & Helpers
        $response->assertSee('id="btnCopyRcResult"', false);
        $response->assertSee('id="btnCopyRcMobileResult"', false);
        $response->assertSee('id="btnPrintRcCard"', false);
        $response->assertSee('printRcSmartCard');

        // Verified Record Details section should remain hidden
        $response->assertSee('id="allSuccessDataCard" class="card border-0 shadow-sm rounded-3 overflow-hidden d-none mb-3"', false);
    }

    /**
     * Test successful execution of RC To Mobile v3 service with UAT endpoint and credentials.
     */
    public function test_rc_to_mobile_v3_service_executes_successfully_and_returns_registered_mobile(): void
    {
        $service = Service::create([
            'name' => 'RC To Mobile v3',
            'service_code' => 'rc_to_mobile_v3',
            'category' => 'Transport & Vahan',
            'description' => 'Fetch vehicle registration details, owner name, chassis number, and registered mobile.',
            'endpoint_path' => 'https://javabackend.idspay.in/api/v1/uat/srv1/rc-to-mobile',
            'http_method' => 'POST',
            'token_cost' => 3.50,
            'is_active' => true,
            'sample_payload' => [
                'api_id' => 'APID3597',
                'api_key' => '679d36c9-1897-41ca-aabb-8549842c33c7',
                'token_id' => 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN',
                'vehicle_num' => 'GJ08BX1234',
            ],
            'required_fields' => ['vehicle_num'],
        ]);

        Http::fake([
            'https://javabackend.idspay.in/api/v1/uat/srv1/rc-to-mobile' => Http::response([
                'status' => [
                    'code' => 200,
                    'type' => 'success',
                    'message' => 'RC To Mobile v3 response processed successfully.',
                ],
                'message' => 'RC To Mobile v3 response processed successfully.',
                'data' => [
                    'requestId' => null,
                    'clientRequestId' => null,
                    'data' => [
                        'mobileNo' => '9xxxxxxx57',
                    ],
                ],
            ], 200),
        ]);

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchise->api_key,
        ])->postJson('/api/v1/service/execute/rc_to_mobile_v3', [
            'vehicle_num' => 'GJ08BX1234',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'service' => [
                    'code' => 'rc_to_mobile_v3',
                    'name' => 'RC To Mobile v3',
                ],
                'idspay_data' => [
                    'data' => [
                        'data' => [
                            'mobileNo' => '9xxxxxxx57',
                        ],
                    ],
                ],
            ]);

        $this->assertEquals(96.50, $this->franchise->fresh()->wallet_balance);
        $this->assertDatabaseHas('api_logs', [
            'service_id' => $service->id,
            'status' => 'SUCCESS',
        ]);
    }

    /**
     * Test Pan Details V2 execution when upstream IDSPay UAT returns HTTP 200 with data: [].
     * Verifies that the controller extracts pan_number and enriches data attributes.
     */
    public function test_pan_details_v2_executes_successfully_and_returns_verified_pan_data(): void
    {
        $service = Service::where('service_code', 'pan_details_v2')->first();
        if (!$service) {
            $service = Service::create([
                'name' => 'Pan Details V2',
                'service_code' => 'pan_details_v2',
                'category' => 'Identity & Tax',
                'description' => 'Comprehensive PAN verification with holder name matching.',
                'endpoint_path' => 'https://javabackend.idspay.in/api/v1/uat/srv2/validation/pan',
                'http_method' => 'POST',
                'token_cost' => 2.50,
                'is_active' => true,
                'sample_payload' => [
                    'api_id' => 'APID3597',
                    'api_key' => '679d36c9-1897-41ca-aabb-8549842c33c7',
                    'token_id' => 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN',
                    'pan' => 'ABCDE1234F',
                    'name' => 'RAJESH KUMAR SHARMA',
                    'pan_display_name' => 'false',
                    'name_match_method' => 'fuzzy',
                ],
                'required_fields' => ['pan', 'name'],
            ]);
        }

        Http::fake([
            'https://javabackend.idspay.in/api/v1/uat/srv2/validation/pan' => Http::response([
                'status' => [
                    'code' => 200,
                    'type' => 'success',
                    'message' => 'Pan Details V2 response processed successfully.',
                ],
                'message' => 'Pan Details V2 response processed successfully.',
                'data' => [],
            ], 200),
        ]);

        $response = $this->withHeaders([
            'X-API-KEY' => $this->franchise->api_key,
        ])->postJson('/api/v1/service/execute/pan_details_v2', [
            'pan' => 'ABCDE1234F',
            'name' => 'RAJESH KUMAR SHARMA',
            'pan_display_name' => 'false',
            'name_match_method' => 'fuzzy',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'SUCCESS',
                'pan_number' => 'ABCDE1234F',
                'service' => [
                    'code' => 'pan_details_v2',
                    'name' => 'Pan Details V2',
                ],
                'idspay_data' => [
                    'data' => [
                        'pan' => 'ABCDE1234F',
                        'fullname' => 'RAJESH KUMAR SHARMA',
                        'first_name' => 'RAJESH',
                        'last_name' => 'SHARMA',
                        'pan_type' => 'Individual (P)',
                        'name_match_method' => 'fuzzy',
                        'name_match_score' => '100%',
                    ],
                ],
            ]);

        $this->assertEquals(97.50, $this->franchise->fresh()->wallet_balance);
        $this->assertDatabaseHas('api_logs', [
            'service_id' => $service->id,
            'status' => 'SUCCESS',
        ]);
    }
}



