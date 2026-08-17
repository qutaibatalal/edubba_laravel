<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_rooms', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('code')->nullable();
            $t->string('location')->nullable();
            $t->integer('capacity')->default(30);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('exam_room_allocations', function (Blueprint $t) {
            $t->id();
            $t->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $t->foreignId('exam_schedule_id')->nullable()->constrained('exam_schedules')->nullOnDelete();
            $t->foreignId('exam_room_id')->constrained('exam_rooms')->cascadeOnDelete();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->string('seat_no')->nullable();
            $t->boolean('attended')->nullable();
            $t->timestamps();

            $t->unique(['exam_schedule_id', 'exam_room_id', 'student_id'], 'exam_alloc_unique');
            $t->index(['exam_id', 'exam_schedule_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_room_allocations');
        Schema::dropIfExists('exam_rooms');
    }
};
