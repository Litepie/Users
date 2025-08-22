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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('user_type')->default('user');
            $table->enum('status', ['pending', 'active', 'inactive', 'suspended', 'banned'])->default('pending');
            
            // Tenant support
            $table->unsignedBigInteger('tenant_id')->nullable();
            
            // Authentication and session tracking
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->string('remember_token')->nullable();
            
            // Localization
            $table->string('timezone')->default('UTC');
            $table->string('locale')->default('en');
            
            // Avatar
            $table->string('avatar_url')->nullable();
            
            // Two-factor authentication
            $table->boolean('two_factor_enabled')->default(false);
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            
            // API access (for system users)
            $table->string('api_key')->nullable()->unique();
            $table->string('api_secret')->nullable();
            
            // Metadata
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['email', 'user_type']);
            $table->index(['status']);
            $table->index(['tenant_id']);
            $table->index(['user_type']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
