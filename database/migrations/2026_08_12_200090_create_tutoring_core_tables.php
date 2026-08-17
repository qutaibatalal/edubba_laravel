<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('centers', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->text('address')->nullable();
            $t->string('phone')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('branches', function (Blueprint $t) {
            $t->id();
            $t->foreignId('center_id')->nullable()->constrained('centers')->nullOnDelete();
            $t->string('name');
            $t->text('address')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('tutors', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone')->nullable();
            $t->string('email')->nullable();
            $t->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $t->json('subjects')->nullable();
            $t->decimal('hourly_rate', 10, 2)->default(0);
            $t->string('state')->default('active');
            $t->timestamps();
        });

        Schema::create('tutor_availabilities', function (Blueprint $t) {
            $t->id();
            $t->foreignId('tutor_id')->constrained('tutors')->cascadeOnDelete();
            $t->foreignId('week_day_id')->constrained('week_days')->cascadeOnDelete();
            $t->time('start_time');
            $t->time('end_time');
            $t->timestamps();
        });

        Schema::create('tutoring_packages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->integer('sessions')->default(0);
            $t->decimal('price', 12, 2)->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('tutoring_products', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('code')->nullable();
            $t->decimal('price', 12, 2)->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('study_groups', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $t->foreignId('tutor_id')->nullable()->constrained('tutors')->nullOnDelete();
            $t->foreignId('center_id')->nullable()->constrained('centers')->nullOnDelete();
            $t->integer('max_students')->default(5);
            $t->string('level')->nullable();
            $t->string('state')->default('active');
            $t->timestamps();
        });

        Schema::create('study_group_students', function (Blueprint $t) {
            $t->id();
            $t->foreignId('study_group_id')->constrained('study_groups')->cascadeOnDelete();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->date('join_date')->nullable();
            $t->string('state')->default('active');
            $t->unique(['study_group_id', 'student_id']);
            $t->timestamps();
        });

        Schema::create('study_group_sessions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('study_group_id')->constrained('study_groups')->cascadeOnDelete();
            $t->foreignId('tutor_id')->nullable()->constrained('tutors')->nullOnDelete();
            $t->date('date');
            $t->time('start_time')->nullable();
            $t->time('end_time')->nullable();
            $t->string('state')->default('scheduled');
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('study_group_attendances', function (Blueprint $t) {
            $t->id();
            $t->foreignId('study_group_session_id')->constrained('study_group_sessions')->cascadeOnDelete();
            $t->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $t->string('status');
            $t->unique(['study_group_session_id', 'student_id']);
            $t->timestamps();
        });

        Schema::create('lead_sources', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('lead_stages', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->integer('sequence')->default(0);
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('leads', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone')->nullable();
            $t->string('email')->nullable();
            $t->foreignId('source_id')->nullable()->constrained('lead_sources')->nullOnDelete();
            $t->foreignId('stage_id')->nullable()->constrained('lead_stages')->nullOnDelete();
            $t->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $t->decimal('expected_value', 12, 2)->default(0);
            $t->string('state')->default('new');
            $t->text('notes')->nullable();
            $t->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $t) {
            $t->id();
            $t->string('reference')->nullable();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->foreignId('parent_id')->nullable()->constrained('parents')->nullOnDelete();
            $t->foreignId('tutor_id')->nullable()->constrained('tutors')->nullOnDelete();
            $t->foreignId('study_group_id')->nullable()->constrained('study_groups')->nullOnDelete();
            $t->foreignId('package_id')->nullable()->constrained('tutoring_packages')->nullOnDelete();
            $t->foreignId('product_id')->nullable()->constrained('tutoring_products')->nullOnDelete();
            $t->date('start_date');
            $t->date('end_date')->nullable();
            $t->string('frequency');
            $t->integer('sessions_count')->default(0);
            $t->integer('sessions_used')->default(0);
            $t->decimal('amount', 12, 2)->default(0);
            $t->decimal('paid_amount', 12, 2)->default(0);
            $t->string('state')->default('draft');
            $t->date('next_renewal_date')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('lead_stages');
        Schema::dropIfExists('lead_sources');
        Schema::dropIfExists('study_group_attendances');
        Schema::dropIfExists('study_group_sessions');
        Schema::dropIfExists('study_group_students');
        Schema::dropIfExists('study_groups');
        Schema::dropIfExists('tutoring_products');
        Schema::dropIfExists('tutoring_packages');
        Schema::dropIfExists('tutor_availabilities');
        Schema::dropIfExists('tutors');
        Schema::dropIfExists('branches');
        Schema::dropIfExists('centers');
    }
};
