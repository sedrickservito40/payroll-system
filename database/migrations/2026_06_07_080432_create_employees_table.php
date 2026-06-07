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
         Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // Employee ID
            $table->string('employee_number')->unique();

            // Name
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();

            // Work Info
            $table->string('department')->nullable();
            $table->string('school')->nullable();
            $table->string('educational_attainment')->nullable();
            $table->string('degree')->nullable();

            // Personal Info
            $table->date('birthdate')->nullable();
            $table->string('birthplace')->nullable();

            // Schedule
            $table->time('shift_in_sched')->nullable();
            $table->time('shift_out_sched')->nullable();

            // Government Numbers
            $table->string('sss_number')->nullable();
            $table->string('philhealth_number')->nullable();
            $table->string('tin_number')->nullable();
            $table->string('pagibig_number')->nullable();

            // Image (WARNING: blob not recommended)
            $table->binary('emp_img')->nullable();
            
            // Employment Info
            $table->string('employee_level')->nullable();
            $table->decimal('employee_rate', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
