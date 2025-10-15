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
        Schema::table('ticket_images', function (Blueprint $table) {
            $table->string('url')->nullable()->after('path')->comment('Public URL of the image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_images', function (Blueprint $table) {
            $table->dropColumn('url');
        });
    }
};
