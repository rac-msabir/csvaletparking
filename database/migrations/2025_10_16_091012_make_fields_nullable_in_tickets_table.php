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
        Schema::table('tickets', function (Blueprint $table) {
            // Make all fields nullable except customer_phone
            $table->string('customer_name')->nullable()->change();
            $table->string('vehicle_make')->nullable()->change();
            $table->string('vehicle_model')->nullable()->change();
            $table->string('vehicle_color')->nullable()->change();
            $table->string('amount')->nullable()->change();
            $table->string('payment_status')->nullable()->change();
            $table->foreignId('created_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Revert back to not nullable (except for fields that were already nullable)
            $table->string('customer_name')->nullable(false)->change();
            $table->string('vehicle_make')->nullable(false)->change();
            $table->string('vehicle_model')->nullable(false)->change();
            $table->string('vehicle_color')->nullable(false)->change();
            $table->string('amount')->nullable(false)->change();
            $table->string('payment_status')->nullable(false)->change();
            $table->foreignId('created_by')->nullable(false)->change();
        });
    }
};
