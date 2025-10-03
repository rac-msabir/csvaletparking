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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('ticket_number')->unique();
            $table->enum('status', ['pending', 'in_progress', 'ready', 'delivered', 'cancelled'])->default('pending');
            
            // Customer information
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            
            // Vehicle information
            $table->string('vehicle_make');
            $table->string('vehicle_model');
            $table->string('vehicle_color');
            $table->string('license_plate')->nullable();
            
            // Parking information
            $table->string('parking_spot')->nullable();
            $table->string('parking_zone')->nullable();
            
            // Ticket details
            $table->text('special_instructions')->nullable();
            $table->text('damage_notes')->nullable();
            
            // Check-in/check-out times
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            
            // Employee tracking
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('delivered_by')->nullable()->constrained('users');
            
            // Payment information
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            
            // QR Code and security
            $table->string('qr_code_path')->nullable();
            $table->string('verification_code', 6)->nullable();
            
            // Location data
            $table->decimal('check_in_latitude', 10, 8)->nullable();
            $table->decimal('check_in_longitude', 11, 8)->nullable();
            $table->decimal('check_out_latitude', 10, 8)->nullable();
            $table->decimal('check_out_longitude', 11, 8)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('ticket_number');
            $table->index('status');
            $table->index('license_plate');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
