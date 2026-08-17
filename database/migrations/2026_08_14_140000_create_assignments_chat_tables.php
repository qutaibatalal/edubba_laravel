<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $t->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $t->string('title');
            $t->text('description')->nullable();
            $t->date('due_date')->nullable();
            $t->string('state')->default('published');
            $t->timestamps();
        });

        Schema::create('assignment_submissions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->string('file')->nullable();
            $t->text('note')->nullable();
            $t->timestamp('submitted_at')->nullable();
            $t->decimal('grade', 5, 2)->nullable();
            $t->text('feedback')->nullable();
            $t->string('state')->default('submitted');
            $t->timestamps();

            $t->unique(['assignment_id', 'student_id']);
        });

        Schema::create('chat_messages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->foreignId('faculty_id')->constrained('faculties')->cascadeOnDelete();
            $t->string('sender')->default('student'); // student | faculty
            $t->text('body');
            $t->timestamp('read_at')->nullable();
            $t->timestamps();

            $t->index(['student_id', 'faculty_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('assignment_submissions');
        Schema::dropIfExists('assignments');
    }
};
