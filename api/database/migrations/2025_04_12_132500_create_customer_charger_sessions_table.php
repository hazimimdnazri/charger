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
        Schema::create('customer_charger_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('customer_charger_id')->constrained('customer_chargers')->onDelete('cascade');
            $table->timestamp('time_initiated');
            $table->timestamp('time_started')->nullable();
            $table->timestamp('time_ended')->nullable();
            $table->enum('status', ['pending', 'active', 'completed', 'failed'])->default('pending');
            $table->tinyInteger('soc_percent')->length(3)->nullable();
            $table->timestamp('soc_updated_at')->nullable();
            $table->float('total_charge_amount', 10, 2)->nullable();
            $table->float('total_charge_kwh', 10, 2)->nullable();
            $table->float('total_charge_duration', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_charger_sessions');
    }
};
