<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parents', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone')->nullable();
            $t->string('mobile')->nullable();
            $t->string('email')->nullable();
            $t->string('national_id')->nullable();
            $t->text('address')->nullable();
            $t->string('occupation')->nullable();
            $t->string('relation')->nullable();
            $t->string('photo')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('students', function (Blueprint $t) {
            $t->id();
            $t->string('student_code')->nullable();
            $t->string('name');
            $t->string('middle_name')->nullable();
            $t->string('last_name')->nullable();
            $t->string('gender')->nullable();
            $t->date('birth_date')->nullable();
            $t->string('birth_place')->nullable();
            $t->string('national_id')->nullable();
            $t->string('residence')->nullable();
            $t->string('marital_status')->nullable();
            $t->string('blood_group')->nullable();
            $t->string('phone')->nullable();
            $t->string('mobile')->nullable();
            $t->string('email')->nullable();
            $t->text('address')->nullable();
            $t->string('city')->nullable();
            $t->string('province')->nullable();
            $t->string('country')->nullable();
            $t->string('zip')->nullable();
            $t->string('photo')->nullable();
            $t->foreignId('partner_id')->nullable();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->foreignId('parent_id')->nullable()->constrained('parents')->nullOnDelete();
            $t->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $t->string('state')->default('draft');
            $t->date('admission_date')->nullable();
            $t->string('roll_no')->nullable();
            $t->text('medical_note')->nullable();
            $t->text('note')->nullable();
            $t->boolean('active')->default(true);
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->softDeletes();
            $t->unique('student_code');
            $t->index(['batch_id', 'academic_year_id']);
        });

        Schema::create('student_parent', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->foreignId('parent_id')->constrained('parents')->cascadeOnDelete();
            $t->string('relation')->nullable();
            $t->boolean('is_main')->default(false);
            $t->boolean('guardian')->default(false);
            $t->string('emergency_contact')->nullable();
            $t->unique(['student_id', 'parent_id']);
        });

        Schema::create('student_course', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->string('state')->default('running');
            $t->decimal('total_fees', 12, 2)->default(0);
            $t->unique(['student_id', 'course_id']);
            $t->timestamps();
        });

        Schema::create('student_excuses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->date('date');
            $t->text('reason');
            $t->string('document')->nullable();
            $t->string('state')->default('pending');
            $t->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_excuses');
        Schema::dropIfExists('student_course');
        Schema::dropIfExists('student_parent');
        Schema::dropIfExists('students');
        Schema::dropIfExists('parents');
    }
};
