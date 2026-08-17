<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_sheets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('session_id')->nullable()->constrained('class_sessions')->nullOnDelete();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->foreignId('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $t->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $t->date('date');
            $t->string('state')->default('draft');
            $t->timestamps();
        });

        Schema::create('attendance_lines', function (Blueprint $t) {
            $t->id();
            $t->foreignId('attendance_sheet_id')->constrained('attendance_sheets')->cascadeOnDelete();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->string('status');
            $t->text('note')->nullable();
            $t->unique(['attendance_sheet_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_lines');
        Schema::dropIfExists('attendance_sheets');
    }
};
