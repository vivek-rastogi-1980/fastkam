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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique()->index();
            $table->text('value')->nullable();
            $table->string('type', 30)->default('string'); // string, float, integer, boolean, json
            $table->string('group', 50)->default('tax'); // tax, pricing, system
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed initial default GST settings (18% Standard GST Rate)
        DB::table('system_settings')->insert([
            [
                'key' => 'gst_rate_percent',
                'value' => '0.00',
                'type' => 'float',
                'group' => 'tax',
                'description' => 'Goods and Services Tax (GST) percentage rate applied to service transactions',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'gst_enabled',
                'value' => '1',
                'type' => 'boolean',
                'group' => 'tax',
                'description' => 'Master switch to enable or disable GST surcharge on service triggers',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
