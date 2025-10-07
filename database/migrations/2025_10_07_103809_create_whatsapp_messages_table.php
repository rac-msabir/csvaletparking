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
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            
            // Customer information
            $table->string('customer_phone');
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            
            // Message content
            $table->text('content');
            $table->string('message_type')->default('outbound'); // 'inbound' or 'outbound'
            $table->string('template_name')->nullable(); // If using WhatsApp templates
            
            // Status tracking
            $table->string('status')->default('pending'); // 'pending', 'sent', 'delivered', 'read', 'failed'
            $table->string('error_message')->nullable();
            $table->integer('attempts')->default(0);
            
            // Twilio specific fields
            $table->string('message_sid')->nullable(); // Twilio message SID
            $table->string('account_sid')->nullable(); // Twilio account SID
            $table->text('twilio_response')->nullable(); // Full Twilio response
            
            // Message metadata
            $table->string('media_urls')->nullable(); // JSON array of media URLs if any
            $table->decimal('cost', 10, 4)->nullable(); // Message cost if applicable
            
            // Timestamps
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['customer_phone', 'status']);
            $table->index('message_sid');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
