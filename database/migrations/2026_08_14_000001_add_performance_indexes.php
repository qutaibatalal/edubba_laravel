<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_lines', function (Blueprint $t) {
            $t->index(['student_id', 'created_at']);
        });

        Schema::table('marksheet_lines', function (Blueprint $t) {
            $t->index(['marksheet_id', 'subject_id']);
        });

        Schema::table('invoices', function (Blueprint $t) {
            $t->index(['student_id', 'state']);
        });

        Schema::table('notification_logs', function (Blueprint $t) {
            $t->index(['student_id', 'created_at']);
        });

        Schema::table('time_table_lines', function (Blueprint $t) {
            $t->index(['week_day_id', 'timing_id']);
        });

        Schema::table('students', function (Blueprint $t) {
            $t->index('student_code');
        });

        Schema::table('invoices', function (Blueprint $t) {
            $t->index('state');
        });

        Schema::table('attendance_lines', function (Blueprint $t) {
            $t->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_lines', function (Blueprint $t) {
            $t->dropIndex(['student_id', 'created_at']);
            $t->dropIndex(['student_id']);
        });

        Schema::table('marksheet_lines', function (Blueprint $t) {
            $t->dropIndex(['marksheet_id', 'subject_id']);
        });

        Schema::table('invoices', function (Blueprint $t) {
            $t->dropIndex(['student_id', 'state']);
            $t->dropIndex(['state']);
        });

        Schema::table('notification_logs', function (Blueprint $t) {
            $t->dropIndex(['student_id', 'created_at']);
        });

        Schema::table('time_table_lines', function (Blueprint $t) {
            $t->dropIndex(['week_day_id', 'timing_id']);
        });

        Schema::table('students', function (Blueprint $t) {
            $t->dropIndex(['student_code']);
        });
    }
};
