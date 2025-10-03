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
        Schema::create('ticket_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users');
            
            // Image details
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('size');
            
            // Image metadata
            $table->string('image_type')->default('vehicle'); // vehicle, damage, document, other
            $table->text('description')->nullable();
            
            // Image processing
            $table->boolean('is_processed')->default(false);
            $table->json('metadata')->nullable();
            
            // Thumbnails and variations
            $table->string('thumbnail_path')->nullable();
            $table->string('medium_path')->nullable();
            
            // Indexes
            $table->index('ticket_id');
            $table->index('image_type');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_images');
    }
};
