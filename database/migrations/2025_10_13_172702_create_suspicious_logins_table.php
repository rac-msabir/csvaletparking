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
        Schema::create('suspicious_logins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('login_latitude', 10, 8);
            $table->decimal('login_longitude', 11, 8);
            $table->decimal('allowed_latitude', 10, 8);
            $table->decimal('allowed_longitude', 11, 8);
            $table->decimal('distance_km', 10, 2);
            $table->string('ip_address', 45);
            $table->string('user_agent')->nullable();
            $table->boolean('notified')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspicious_logins');
    }
};
