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
        Schema::table('users', function (Blueprint $table) {
            
            $table->unsignedBigInteger('tenant_id')->after('id')->nullable()->constrained()->onDelete('set null');
            
            // Add user details
            $table->string('phone')->after('email')->nullable();
            $table->string('job_title')->after('name')->nullable();
            $table->string('timezone')->after('remember_token')->default('UTC');
            $table->string('locale', 10)->after('timezone')->default('en');
            $table->boolean('is_active')->after('locale')->default(true);
            $table->string('last_login_at')->after('is_active')->nullable();
            $table->string('last_login_ip')->after('last_login_at')->nullable();
            
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'tenant_id',
                'phone',
                'job_title',
                'timezone',
                'locale',
                'is_active',
                'last_login_at',
                'last_login_ip',
            ]);
            
            // Revert email_verified_at to not nullable if needed
            $table->timestamp('email_verified_at')->nullable(false)->change();
        });
    }
};
