<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('week_days', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->integer('sequence')->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('timings', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->time('start_time');
            $t->time('end_time');
            $t->integer('sequence')->default(0);
            $t->timestamps();
        });

        Schema::create('time_tables', function (Blueprint $t) {
            $t->id();
            $t->foreignId('batch_id')->constrained('batches')->cascadeOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->foreignId('term_id')->nullable()->constrained('terms')->nullOnDelete();
            $t->string('name');
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('time_table_lines', function (Blueprint $t) {
            $t->id();
            $t->foreignId('time_table_id')->constrained('time_tables')->cascadeOnDelete();
            $t->foreignId('week_day_id')->constrained('week_days')->cascadeOnDelete();
            $t->foreignId('timing_id')->constrained('timings')->cascadeOnDelete();
            $t->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $t->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $t->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $t->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('class_sessions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('time_table_line_id')->nullable()->constrained('time_table_lines')->nullOnDelete();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $t->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $t->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $t->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            $t->date('date');
            $t->time('start_time')->nullable();
            $t->time('end_time')->nullable();
            $t->string('state')->default('planned');
            $t->text('topic')->nullable();
            $t->text('notes')->nullable();
            $t->timestamps();
            $t->index(['batch_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_sessions');
        Schema::dropIfExists('time_table_lines');
        Schema::dropIfExists('time_tables');
        Schema::dropIfExists('timings');
        Schema::dropIfExists('week_days');
    }
};
