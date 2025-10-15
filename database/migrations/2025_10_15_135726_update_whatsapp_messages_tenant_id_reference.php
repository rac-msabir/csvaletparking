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
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['tenant_id']);
            
            // Re-add the column with the new foreign key constraint
            $table->foreignId('tenant_id')
                  ->nullable()
                  ->change()
                  ->constrained('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            // Drop the current foreign key
            $table->dropForeign(['tenant_id']);
            
            // Re-add the original foreign key constraint
            $table->foreignId('tenant_id')
                  ->change()
                  ->constrained('tenants')
                  ->onDelete('cascade');
        });
    }
};
