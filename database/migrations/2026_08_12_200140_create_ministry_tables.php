<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iraqi_calendars', function (Blueprint $t) {
            $t->id();
            $t->date('gregorian_date')->unique();
            $t->string('hijri_date')->nullable();
            $t->string('iraqi_name')->nullable();
            $t->boolean('is_holiday')->default(false);
            $t->text('description')->nullable();
        });

        Schema::create('holidays', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->date('date_start');
            $t->date('date_stop');
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('ministry_reports', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->foreignId('term_id')->nullable()->constrained('terms')->nullOnDelete();
            $t->string('report_type');
            $t->json('data')->nullable();
            $t->string('state')->default('draft');
            $t->timestamps();
        });

        Schema::create('student_daily_attendance_registers', function (Blueprint $t) {
            $t->id();
            $t->foreignId('batch_id')->nullable()->constrained('batches')->nullOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->date('date');
            $t->integer('present_count')->default(0);
            $t->integer('absent_count')->default(0);
            $t->integer('total_count')->default(0);
            $t->json('details')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_daily_attendance_registers');
        Schema::dropIfExists('ministry_reports');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('iraqi_calendars');
    }
};
