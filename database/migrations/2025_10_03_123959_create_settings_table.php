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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->onDelete('cascade');
            
            // General Settings
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, number, boolean, json, text, etc.
            $table->string('group')->default('general'); // general, email, notifications, etc.
            $table->text('description')->nullable();
            
            // For settings that should be encrypted
            $table->boolean('is_encrypted')->default(false);
            
            // For settings that should be visible in the UI
            $table->boolean('is_public')->default(true);
            
            // For settings that should be cached
            $table->boolean('is_cached')->default(true);
            
            // For settings that should be editable in the UI
            $table->boolean('is_editable')->default(true);
            
            // For settings that should be visible in the UI
            $table->boolean('is_visible')->default(true);
            
            // For settings that should be unique per tenant
            $table->boolean('is_tenant_setting')->default(false);
            
            // For settings that should be unique per user
            $table->boolean('is_user_setting')->default(false);
            
            // For settings that should be unique per team
            $table->boolean('is_team_setting')->default(false);
            
            // For settings that should be unique per role
            $table->boolean('is_role_setting')->default(false);
            
            // For settings that should be unique per permission
            $table->boolean('is_permission_setting')->default(false);
            
            // For settings that should be unique per model
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            
            // Indexes
            $table->index(['key', 'tenant_id']);
            $table->index(['group', 'is_public', 'is_editable']);
            $table->index(['model_type', 'model_id']);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
