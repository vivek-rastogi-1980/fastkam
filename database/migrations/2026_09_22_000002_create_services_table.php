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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('service_code', 50)->unique()->index();
            $table->string('category', 50)->default('Verification');
            $table->text('description')->nullable();
            $table->string('endpoint_path'); // e.g. /api/v1/pan/aadhaar or full relative path for IDSPay
            $table->string('http_method', 10)->default('POST');
            $table->decimal('token_cost', 8, 2)->default(1.00); // 1 INR = 1 Token
            $table->boolean('is_active')->default(true)->index();
            $table->json('sample_payload')->nullable();
            $table->json('required_fields')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
