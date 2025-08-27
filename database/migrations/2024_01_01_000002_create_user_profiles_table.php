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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Tenant support
            $table->unsignedBigInteger('tenant_id')->nullable();
            
            // Personal information
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable();
            $table->string('website')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other', 'prefer_not_to_say'])->nullable();
            
            // Address information
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            
            // Emergency contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            // Organization hierarchy fields
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained('organization_departments')->onDelete('set null');
            $table->foreignId('position_id')->nullable()->constrained('organization_positions')->onDelete('set null');
            $table->string('organization_role')->nullable(); // manager, supervisor, staff, intern
            $table->string('employee_id')->nullable()->unique();
            $table->string('department')->nullable(); // Can be overridden by organization department
            $table->string('division')->nullable();
            $table->string('team')->nullable();
            $table->date('hire_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->string('employment_status')->default('active'); // active, inactive, terminated, suspended
            $table->string('employment_type')->nullable(); // full-time, part-time, contract, freelance
            $table->decimal('salary', 10, 2)->nullable();
            $table->string('salary_currency', 3)->default('USD');
            $table->json('reporting_structure')->nullable(); // Store reporting hierarchy
            $table->json('permissions')->nullable(); // Organization-specific permissions
            $table->json('organization_metadata')->nullable(); // Org-specific data
            
            // Social and preferences
            $table->json('social_links')->nullable();
            $table->json('preferences')->nullable();
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['user_id']);
            $table->index(['tenant_id']);
            $table->index(['first_name', 'last_name']);
            $table->index(['company']);
            $table->index(['organization_id']);
            $table->index(['organization_id', 'organization_role']);
            $table->index(['employee_id']);
            $table->index(['employment_status']);
            $table->index(['hire_date']);
            $table->index(['department', 'division', 'team']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
