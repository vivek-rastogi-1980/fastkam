<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Core Services (6 Integrated IDSPay Services with Essential Inputs)
        $services = [
            [
                'name' => 'DL PDF BY DL NO',
                'service_code' => 'dl_verification_plus_srv2',
                'category' => 'Transport & Vahan',
                'description' => 'DL PDF INSTANT DOWNLOAD KARO',
                'endpoint_path' => 'https://javabackend.idspay.in/api/v1/uat/srv2/validation/dl-pdf',
                'http_method' => 'POST',
                'token_cost' => 8.00,
                'super_admin_cost' => 8.00,
                'default_franchise_price' => 8.00,
                'is_active' => true,
                'sample_payload' => [
                    'api_id' => 'APID3597',
                    'api_key' => '679d36c9-1897-41ca-aabb-8549842c33c7',
                    'token_id' => 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN',
                    'dlNumber' => 'DL-1420110012345',
                    'dob' => '1990-05-15',
                ],
                'required_fields' => ['dlNumber', 'dob'],
            ],
            [
                'name' => 'Pan Details V2',
                'service_code' => 'pan_details_v2',
                'category' => 'Identity & Tax',
                'description' => 'Comprehensive PAN verification with full holder name matching, fuzzy algorithm scoring, and active status.',
                'endpoint_path' => 'https://javabackend.idspay.in/api/v1/uat/srv2/validation/pan',
                'http_method' => 'POST',
                'token_cost' => 25.50,
                'super_admin_cost' => 2.50,
                'default_franchise_price' => 25.50,
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
            ],
            [
                'name' => 'Pan details V1',
                'service_code' => 'pan_details_v1',
                'category' => 'Identity & Tax',
                'description' => 'Fast real-time PAN card validation, operational status check, and registered holder details with father name.',
                'endpoint_path' => 'https://javabackend.idspay.in/api/v1/uat/srv2/validation/pan/father-details',
                'http_method' => 'POST',
                'token_cost' => 20.00,
                'super_admin_cost' => 2.00,
                'default_franchise_price' => 20.00,
                'is_active' => true,
                'sample_payload' => [
                    'api_id' => 'APID3597',
                    'api_key' => '679d36c9-1897-41ca-aabb-8549842c33c7',
                    'token_id' => 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN',
                    'pan' => 'ABCDE1234F',
                ],
                'required_fields' => ['pan'],
            ],
            [
                'name' => 'Aadhar To Pan',
                'service_code' => 'aadhar_to_pan',
                'category' => 'Identity & Tax',
                'description' => 'Retrieve linked PAN card number and Income Tax Department filing status using 12-digit Aadhaar.',
                'endpoint_path' => '/srv2/validation/aadhaar-to-unmasked-pan',
                'http_method' => 'POST',
                'token_cost' => 50.00,
                'super_admin_cost' => 5.00,
                'default_franchise_price' => 50.00,
                'is_active' => true,
                'sample_payload' => [
                    'api_id' => 'APID3597',
                    'api_key' => '679d36c9-1897-41ca-aabb-8549842c33c7',
                    'token_id' => 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN',
                    'aadhar' => '521234567897',
                ],
                'required_fields' => ['aadhar'],
            ],
            [
                'name' => 'RC To Mobile v3',
                'service_code' => 'rc_to_mobile_v3',
                'category' => 'Transport & Vahan',
                'description' => 'Fetch vehicle registration details, owner name, chassis number, and registered mobile.',
                'endpoint_path' => '/srv1/rc-to-mobile',
                'http_method' => 'POST',
                'token_cost' => 35.50,
                'super_admin_cost' => 3.50,
                'default_franchise_price' => 35.50,
                'is_active' => true,
                'sample_payload' => [
                    'api_id' => 'APID3597',
                    'api_key' => '679d36c9-1897-41ca-aabb-8549842c33c7',
                    'token_id' => 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN',
                    'vehicle_num' => 'GJ08BX1234',
                ],
                'required_fields' => ['vehicle_num'],
            ],
            [
                'name' => 'Voter ID Card Verification (Srv2)',
                'service_code' => 'voter_id_verification_srv2',
                'category' => 'Election Commission',
                'description' => 'Verify Electoral Photo Identity Card (EPIC) voter ID, assembly constituency, and polling station.',
                'endpoint_path' => 'https://javabackend.idspay.in/api/v1/uat/srv2/validation/voter',
                'http_method' => 'POST',
                'token_cost' => 20.00,
                'super_admin_cost' => 2.00,
                'default_franchise_price' => 20.00,
                'is_active' => true,
                'sample_payload' => [
                    'api_id' => 'APID3597',
                    'api_key' => '679d36c9-1897-41ca-aabb-8549842c33c7',
                    'token_id' => 'ZvFfehk2E2JNyenRuGKyDBfSAIQHJ9lN',
                    'voterId' => 'ABC1234567',
                ],
                'required_fields' => ['voterId'],
            ],
        ];

        // Clean out legacy services and replace with the 6 updated essential IDSPay services
        $existingCodes = collect($services)->pluck('service_code')->toArray();
        Service::whereNotIn('service_code', $existingCodes)->delete();

        foreach ($services as $serviceData) {
            Service::updateOrCreate(
                ['service_code' => $serviceData['service_code']],
                $serviceData
            );
        }

        // 2. Seed Master Admin Account
        $admin = User::updateOrCreate(
            ['email' => 'admin@a4Digital.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '9876543210',
                'address' => 'a4Digital HQ, Tech Hub, Sector 62, Noida, UP',
                'dob' => '1985-01-15',
                'gov_id_type' => 'pan',
                'gov_id_number' => 'ABCDE1234F',
                'api_key' => 'vle_admin_' . Str::random(32),
                'api_secret' => Str::random(40),
                'wallet_balance' => 50000.00,
                'status' => 'active',
            ]
        );

        // 3. Seed Demo Franchise Account
        $franchise = User::updateOrCreate(
            ['email' => 'franchise@a4Digital.com'],
            [
                'name' => 'Apex Digital Franchise Kendra',
                'password' => Hash::make('franchise123'),
                'role' => 'franchise',
                'phone' => '9123456780',
                'address' => 'Shop 4, Market Complex, MG Road, Jaipur, Rajasthan',
                'dob' => '1992-08-20',
                'gov_id_type' => 'aadhaar',
                'gov_id_number' => '456789012345',
                'api_key' => 'vle_live_8f3d1b9a7c4e2098d5a1f6e2c3b4a5d6',
                'api_secret' => 'sec_' . Str::random(32),
                'wallet_balance' => 1000.00, // 1000 INR = 1000 Tokens
                'status' => 'active',
            ]
        );

        // Initial wallet credit log for demo franchise
        if ($franchise->walletTransactions()->count() === 0) {
            WalletTransaction::create([
                'user_id' => $franchise->id,
                'transaction_id' => 'TXN_INIT_' . strtoupper(uniqid()),
                'type' => 'credit',
                'amount' => 1000.00,
                'opening_balance' => 0.00,
                'closing_balance' => 1000.00,
                'description' => 'Initial franchise onboarding wallet credit (1000 Tokens)',
                'reference_id' => 'PGW_ONBOARD_INIT',
                'metadata' => ['gateway' => 'Razorpay / Cashfree', 'mode' => 'System Credit'],
            ]);
        }

        // Initialize default service prices for the demo franchise
        $franchise->ensureDefaultServicePrices();

        // 4. Seed Standard GST Tax Settings (18% Standard Services Rate)
        \App\Models\SystemSetting::set('gst_rate_percent', 18.00, 'float', 'tax', 'Goods and Services Tax (GST) percentage rate applied to service transactions');
        \App\Models\SystemSetting::set('gst_enabled', true, 'boolean', 'tax', 'Master switch to enable or disable GST surcharge on service triggers');
    }
}
