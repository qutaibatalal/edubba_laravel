<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->foreignId('class_teacher_id')->nullable()->constrained('faculties')->nullOnDelete();
            $t->integer('capacity')->default(30);
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('classrooms', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('building')->nullable();
            $t->integer('floor')->nullable();
            $t->integer('capacity')->default(30);
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('courses', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('code')->nullable();
            $t->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $t->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $t->integer('credit_hours')->nullable();
            $t->text('syllabus')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('batches');
    }
};
