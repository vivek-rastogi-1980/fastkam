<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('franchise')->after('email');
            $table->string('phone', 20)->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
            $table->date('dob')->nullable()->after('address');
            $table->string('gov_id_type', 30)->nullable()->after('dob'); // aadhaar, pan, voter_id, etc.
            $table->string('gov_id_number', 50)->nullable()->after('gov_id_type');
            $table->string('api_key', 128)->nullable()->unique()->after('gov_id_number');
            $table->string('api_secret', 128)->nullable()->after('api_key');
            $table->decimal('wallet_balance', 12, 2)->default(0.00)->after('api_secret');
            $table->string('status', 20)->default('active')->after('wallet_balance'); // active, suspended, pending
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'address',
                'dob',
                'gov_id_type',
                'gov_id_number',
                'api_key',
                'api_secret',
                'wallet_balance',
                'status',
            ]);
        });
    }
};
