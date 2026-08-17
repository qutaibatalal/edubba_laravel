<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_courses', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('code')->nullable();
            $t->text('description')->nullable();
            $t->integer('duration_hours')->nullable();
            $t->decimal('price', 12, 2)->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('training_venues', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->text('address')->nullable();
            $t->integer('capacity')->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('trainers', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone')->nullable();
            $t->string('email')->nullable();
            $t->string('specialization')->nullable();
            $t->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $t->decimal('hourly_rate', 10, 2)->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('training_curriculums', function (Blueprint $t) {
            $t->id();
            $t->foreignId('training_course_id')->constrained('training_courses')->cascadeOnDelete();
            $t->string('name');
            $t->text('description')->nullable();
            $t->timestamps();
        });

        Schema::create('training_modules', function (Blueprint $t) {
            $t->id();
            $t->foreignId('training_curriculum_id')->constrained('training_curriculums')->cascadeOnDelete();
            $t->string('name');
            $t->integer('duration_hours')->nullable();
            $t->integer('sequence')->default(0);
            $t->timestamps();
        });

        Schema::create('training_enrollments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('training_course_id')->constrained('training_courses')->cascadeOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->foreignId('participant_id')->nullable();
            $t->date('enroll_date');
            $t->string('state')->default('draft');
            $t->decimal('amount_paid', 12, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('training_schedules', function (Blueprint $t) {
            $t->id();
            $t->foreignId('training_course_id')->constrained('training_courses')->cascadeOnDelete();
            $t->foreignId('trainer_id')->nullable()->constrained('trainers')->nullOnDelete();
            $t->foreignId('venue_id')->nullable()->constrained('training_venues')->nullOnDelete();
            $t->date('start_date');
            $t->date('end_date')->nullable();
            $t->string('state')->default('planned');
            $t->timestamps();
        });

        Schema::create('training_sessions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('training_course_id')->constrained('training_courses')->cascadeOnDelete();
            $t->foreignId('trainer_id')->nullable()->constrained('trainers')->nullOnDelete();
            $t->foreignId('venue_id')->nullable()->constrained('training_venues')->nullOnDelete();
            $t->foreignId('schedule_id')->nullable()->constrained('training_schedules')->nullOnDelete();
            $t->date('date');
            $t->time('start_time')->nullable();
            $t->time('end_time')->nullable();
            $t->string('state')->default('planned');
            $t->text('topic')->nullable();
            $t->timestamps();
        });

        Schema::create('training_attendances', function (Blueprint $t) {
            $t->id();
            $t->foreignId('training_session_id')->constrained('training_sessions')->cascadeOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->string('status');
            $t->unique(['training_session_id', 'student_id']);
            $t->timestamps();
        });

        Schema::create('training_assessments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('training_course_id')->constrained('training_courses')->cascadeOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->string('name');
            $t->date('date');
            $t->decimal('max_marks', 8, 2)->default(0);
            $t->decimal('marks', 8, 2)->default(0);
            $t->boolean('passed')->default(false);
            $t->timestamps();
        });

        Schema::create('training_certificates', function (Blueprint $t) {
            $t->id();
            $t->string('certificate_no')->nullable();
            $t->foreignId('training_enrollment_id')->constrained('training_enrollments')->cascadeOnDelete();
            $t->date('issued_date');
            $t->string('document')->nullable();
            $t->timestamps();
        });

        Schema::create('training_materials', function (Blueprint $t) {
            $t->id();
            $t->foreignId('training_course_id')->constrained('training_courses')->cascadeOnDelete();
            $t->foreignId('module_id')->nullable()->constrained('training_modules')->nullOnDelete();
            $t->string('title');
            $t->string('file')->nullable();
            $t->text('description')->nullable();
            $t->timestamps();
        });

        Schema::create('training_payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('training_enrollment_id')->constrained('training_enrollments')->cascadeOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->date('date');
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('method')->nullable();
            $t->string('state')->default('draft');
            $t->timestamps();
        });

        Schema::create('instructor_payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('trainer_id')->constrained('trainers')->cascadeOnDelete();
            $t->date('period_start');
            $t->date('period_end');
            $t->decimal('hours', 8, 2)->default(0);
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('state')->default('draft');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instructor_payments');
        Schema::dropIfExists('training_payments');
        Schema::dropIfExists('training_materials');
        Schema::dropIfExists('training_certificates');
        Schema::dropIfExists('training_assessments');
        Schema::dropIfExists('training_attendances');
        Schema::dropIfExists('training_sessions');
        Schema::dropIfExists('training_schedules');
        Schema::dropIfExists('training_enrollments');
        Schema::dropIfExists('training_modules');
        Schema::dropIfExists('training_curriculums');
        Schema::dropIfExists('trainers');
        Schema::dropIfExists('training_venues');
        Schema::dropIfExists('training_courses');
    }
};
