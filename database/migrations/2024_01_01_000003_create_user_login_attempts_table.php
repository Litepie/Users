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
        Schema::create('user_login_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('email');
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->boolean('successful')->default(false);
            $table->timestamp('attempted_at');
            $table->string('location')->nullable();
            $table->json('device_info')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id', 'attempted_at']);
            $table->index(['ip_address', 'attempted_at']);
            $table->index(['email', 'attempted_at']);
            $table->index(['successful']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_login_attempts');
    }
};
