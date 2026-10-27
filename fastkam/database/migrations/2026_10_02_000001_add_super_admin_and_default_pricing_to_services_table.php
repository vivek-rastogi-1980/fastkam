<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->decimal('super_admin_cost', 8, 2)->nullable()->after('token_cost');
            $table->decimal('default_franchise_price', 8, 2)->nullable()->after('super_admin_cost');
        });

        // Initialize existing rows with current token_cost
        DB::statement('UPDATE services SET super_admin_cost = token_cost WHERE super_admin_cost IS NULL');
        DB::statement('UPDATE services SET default_franchise_price = token_cost WHERE default_franchise_price IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['super_admin_cost', 'default_franchise_price']);
        });
    }
};
