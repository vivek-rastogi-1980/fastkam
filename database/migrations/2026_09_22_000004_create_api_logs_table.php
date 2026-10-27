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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null');
            $table->string('service_code', 50)->index();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->decimal('tokens_deducted', 12, 2)->default(0.00);
            $table->string('status', 30)->index(); // SUCCESS, FAILED, TIMEOUT, INSUFFICIENT_FUNDS, ERROR
            $table->integer('http_status_code')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->integer('execution_time_ms')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
