<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admission_registers', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->date('start_date')->nullable();
            $t->date('end_date')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('admissions', function (Blueprint $t) {
            $t->id();
            $t->string('application_no')->nullable();
            $t->foreignId('register_id')->nullable()->constrained('admission_registers')->nullOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $t->string('name');
            $t->string('middle_name')->nullable();
            $t->string('last_name')->nullable();
            $t->date('birth_date')->nullable();
            $t->string('gender')->nullable();
            $t->string('national_id')->nullable();
            $t->string('phone')->nullable();
            $t->string('email')->nullable();
            $t->text('address')->nullable();
            $t->string('previous_school')->nullable();
            $t->decimal('fees_amount', 12, 2)->default(0);
            $t->string('state')->default('draft');
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->text('notes')->nullable();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admissions');
        Schema::dropIfExists('admission_registers');
    }
};
