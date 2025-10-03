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
            if (!Schema::hasColumn('users', 'is_tenant_admin')) {
                $table->boolean('is_tenant_admin')->default(false)->after('is_super_admin');
            }
            if (!Schema::hasColumn('users', 'is_employee')) {
                $table->boolean('is_employee')->default(false)->after('is_tenant_admin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_tenant_admin', 'is_employee', 'is_active']);
        });
    }
};
