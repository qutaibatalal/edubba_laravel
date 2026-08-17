<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_types', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->decimal('weight', 5, 2)->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('exams', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('exam_type_id')->nullable()->constrained('exam_types')->nullOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->foreignId('term_id')->nullable()->constrained('terms')->nullOnDelete();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->date('date_start')->nullable();
            $t->date('date_end')->nullable();
            $t->string('state')->default('draft');
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('exam_schedules', function (Blueprint $t) {
            $t->id();
            $t->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $t->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $t->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $t->date('date');
            $t->time('start_time')->nullable();
            $t->time('end_time')->nullable();
            $t->decimal('max_marks', 8, 2)->default(0);
            $t->decimal('pass_marks', 8, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('marksheets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->decimal('total_marks', 8, 2)->default(0);
            $t->decimal('obtained_marks', 8, 2)->default(0);
            $t->decimal('percentage', 5, 2)->default(0);
            $t->string('grade')->nullable();
            $t->string('result')->default('fail');
            $t->integer('rank')->nullable();
            $t->string('state')->default('draft');
            $t->timestamps();
            $t->unique(['exam_id', 'student_id']);
        });

        Schema::create('marksheet_lines', function (Blueprint $t) {
            $t->id();
            $t->foreignId('marksheet_id')->constrained('marksheets')->cascadeOnDelete();
            $t->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $t->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $t->decimal('max_marks', 8, 2)->default(0);
            $t->decimal('marks', 8, 2)->default(0);
            $t->decimal('pass_marks', 8, 2)->default(0);
            $t->decimal('percentage', 5, 2)->default(0);
            $t->string('grade')->nullable();
            $t->boolean('passed')->default(true);
        });

        Schema::create('exam_results', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->foreignId('exam_id')->nullable()->constrained('exams')->nullOnDelete();
            $t->foreignId('term_id')->nullable()->constrained('terms')->nullOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->decimal('total', 10, 2)->default(0);
            $t->decimal('average', 8, 2)->default(0);
            $t->string('grade')->nullable();
            $t->integer('rank')->nullable();
            $t->string('result')->default('fail');
            $t->timestamps();
        });

        Schema::create('question_banks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $t->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $t->text('question');
            $t->string('type');
            $t->text('answer')->nullable();
            $t->json('options')->nullable();
            $t->integer('marks')->default(1);
            $t->string('difficulty')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_banks');
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('marksheet_lines');
        Schema::dropIfExists('marksheets');
        Schema::dropIfExists('exam_schedules');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exam_types');
    }
};
