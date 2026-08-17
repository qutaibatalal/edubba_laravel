<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
            $t->date('date');
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('method')->nullable();
            $t->string('transaction_id')->nullable();
            $t->string('state')->default('draft');
            $t->timestamps();
        });

        Schema::create('subscription_renewals', function (Blueprint $t) {
            $t->id();
            $t->foreignId('subscription_id')->constrained('subscriptions')->cascadeOnDelete();
            $t->date('renewal_date');
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('state')->default('pending');
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('commissions', function (Blueprint $t) {
            $t->id();
            $t->string('reference')->nullable();
            $t->foreignId('tutor_id')->nullable()->constrained('tutors')->nullOnDelete();
            $t->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete();
            $t->decimal('base_amount', 12, 2)->default(0);
            $t->decimal('rate', 5, 2)->default(0);
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('state')->default('draft');
            $t->timestamps();
        });

        Schema::create('commission_lines', function (Blueprint $t) {
            $t->id();
            $t->foreignId('commission_id')->constrained('commissions')->cascadeOnDelete();
            $t->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            $t->decimal('amount', 12, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('tutor_performances', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tutor_id')->constrained('tutors')->cascadeOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->integer('sessions')->default(0);
            $t->integer('students')->default(0);
            $t->decimal('rating', 3, 2)->default(0);
            $t->decimal('attendance_rate', 5, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('wallets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->foreignId('parent_id')->nullable()->constrained('parents')->nullOnDelete();
            $t->decimal('balance', 12, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('wallet_transactions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('wallet_id')->constrained('wallets')->cascadeOnDelete();
            $t->string('type');
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('reference')->nullable();
            $t->text('description')->nullable();
            $t->timestamps();
        });

        Schema::create('resources', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('type')->nullable();
            $t->text('description')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('resource_bookings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('resource_id')->constrained('resources')->cascadeOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->date('date');
            $t->time('start_time');
            $t->time('end_time');
            $t->string('state')->default('booked');
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('auto_assignment_rules', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $t->integer('priority')->default(0);
            $t->integer('max_students')->default(5);
            $t->string('rule_type')->default('lead');
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('tutoring_contracts', function (Blueprint $t) {
            $t->id();
            $t->string('reference')->nullable();
            $t->foreignId('tutor_id')->nullable()->constrained('tutors')->nullOnDelete();
            $t->date('start_date');
            $t->date('end_date')->nullable();
            $t->decimal('hourly_rate', 10, 2)->default(0);
            $t->decimal('commission_rate', 5, 2)->default(0);
            $t->string('state')->default('draft');
            $t->text('terms')->nullable();
            $t->timestamps();
        });

        Schema::create('tutor_payouts', function (Blueprint $t) {
            $t->id();
            $t->string('reference')->nullable();
            $t->foreignId('tutor_id')->constrained('tutors')->cascadeOnDelete();
            $t->date('period_start');
            $t->date('period_end');
            $t->decimal('total_hours', 8, 2)->default(0);
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('state')->default('draft');
            $t->timestamps();
        });

        Schema::create('tutor_payout_lines', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tutor_payout_id')->constrained('tutor_payouts')->cascadeOnDelete();
            $t->foreignId('study_group_session_id')->nullable()->constrained('study_group_sessions')->nullOnDelete();
            $t->decimal('hours', 8, 2)->default(0);
            $t->decimal('rate', 10, 2)->default(0);
            $t->decimal('amount', 12, 2)->default(0);
            $t->timestamps();
        });

        Schema::create('student_progresses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $t->decimal('score', 8, 2)->default(0);
            $t->string('level')->nullable();
            $t->text('notes')->nullable();
            $t->date('recorded_on');
            $t->timestamps();
        });

        Schema::create('assessments', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->foreignId('tutor_id')->nullable()->constrained('tutors')->nullOnDelete();
            $t->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $t->date('date');
            $t->decimal('max_marks', 8, 2)->default(0);
            $t->string('state')->default('draft');
            $t->timestamps();
        });

        Schema::create('assessment_results', function (Blueprint $t) {
            $t->id();
            $t->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->decimal('marks', 8, 2)->default(0);
            $t->string('grade')->nullable();
            $t->text('feedback')->nullable();
            $t->timestamps();
        });

        Schema::create('tutoring_session_feedbacks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('study_group_session_id')->constrained('study_group_sessions')->cascadeOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->integer('rating')->default(0);
            $t->text('comment')->nullable();
            $t->timestamps();
        });

        Schema::create('payment_reminders', function (Blueprint $t) {
            $t->id();
            $t->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            $t->date('remind_date');
            $t->string('channel')->nullable();
            $t->string('state')->default('pending');
            $t->timestamps();
        });

        Schema::create('complaint_categories', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('complaints', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->nullable()->constrained('complaint_categories')->nullOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->string('subject');
            $t->text('description');
            $t->string('state')->default('open');
            $t->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('support_tickets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->string('subject');
            $t->text('description');
            $t->string('priority')->default('normal');
            $t->string('state')->default('open');
            $t->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('tutoring_reports', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('report_type');
            $t->json('data')->nullable();
            $t->string('state')->default('draft');
            $t->timestamps();
        });

        Schema::create('tutoring_dashboard_configs', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('config_key')->unique();
            $t->json('config')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('tutoring_session_invoices', function (Blueprint $t) {
            $t->id();
            $t->string('reference')->nullable();
            $t->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            $t->foreignId('study_group_session_id')->nullable()->constrained('study_group_sessions')->nullOnDelete();
            $t->decimal('amount', 12, 2)->default(0);
            $t->string('state')->default('draft');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutoring_session_invoices');
        Schema::dropIfExists('tutoring_dashboard_configs');
        Schema::dropIfExists('tutoring_reports');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('complaint_categories');
        Schema::dropIfExists('payment_reminders');
        Schema::dropIfExists('tutoring_session_feedbacks');
        Schema::dropIfExists('assessment_results');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('student_progresses');
        Schema::dropIfExists('tutor_payout_lines');
        Schema::dropIfExists('tutor_payouts');
        Schema::dropIfExists('tutoring_contracts');
        Schema::dropIfExists('auto_assignment_rules');
        Schema::dropIfExists('resource_bookings');
        Schema::dropIfExists('resources');
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('tutor_performances');
        Schema::dropIfExists('commission_lines');
        Schema::dropIfExists('commissions');
        Schema::dropIfExists('subscription_renewals');
        Schema::dropIfExists('subscription_payments');
    }
};
