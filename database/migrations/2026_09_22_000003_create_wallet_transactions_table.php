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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained('services')->onDelete('set null');
            $table->string('transaction_id', 64)->unique()->index();
            $table->enum('type', ['credit', 'debit']);
            $table->decimal('amount', 12, 2);
            $table->decimal('opening_balance', 12, 2);
            $table->decimal('closing_balance', 12, 2);
            $table->string('description');
            $table->string('reference_id', 100)->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
