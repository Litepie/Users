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
            // Primary organization relationship
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('set null');
            
            // Organization hierarchy position
            $table->string('organization_position')->nullable(); // CEO, Manager, Staff, Intern
            $table->boolean('is_organization_admin')->default(false);
            $table->boolean('is_organization_owner')->default(false);
            
            // Direct reporting relationships
            $table->foreignId('reports_to_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('primary_manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('secondary_manager_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Organization-specific settings
            $table->json('organization_permissions')->nullable();
            $table->json('organization_settings')->nullable();
            $table->timestamp('organization_joined_at')->nullable();
            $table->timestamp('organization_left_at')->nullable();
            
            // Work schedule and availability
            $table->json('work_schedule')->nullable(); // Store working hours, days, timezone
            $table->string('work_location')->nullable(); // remote, office, hybrid
            $table->string('office_location')->nullable();
            
            // Indexes for performance
            $table->index(['organization_id']);
            $table->index(['organization_id', 'is_organization_admin']);
            $table->index(['organization_id', 'is_organization_owner']);
            $table->index(['reports_to_user_id']);
            $table->index(['primary_manager_id']);
            $table->index(['organization_position']);
            $table->index(['work_location']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropForeign(['reports_to_user_id']);
            $table->dropForeign(['primary_manager_id']);
            $table->dropForeign(['secondary_manager_id']);
            
            $table->dropColumn([
                'organization_id',
                'organization_position',
                'is_organization_admin',
                'is_organization_owner',
                'reports_to_user_id',
                'primary_manager_id',
                'secondary_manager_id',
                'organization_permissions',
                'organization_settings',
                'organization_joined_at',
                'organization_left_at',
                'work_schedule',
                'work_location',
                'office_location',
            ]);
        });
    }
};
