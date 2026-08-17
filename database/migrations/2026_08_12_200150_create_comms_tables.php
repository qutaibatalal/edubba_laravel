<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_users', function (Blueprint $t) {
            $t->id();
            $t->string('username')->unique();
            $t->string('password');
            $t->string('role');
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->foreignId('parent_id')->nullable()->constrained('parents')->nullOnDelete();
            $t->foreignId('faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $t->boolean('active')->default(true);
            $t->rememberToken();
            $t->timestamps();
        });

        Schema::create('notification_logs', function (Blueprint $t) {
            $t->id();
            $t->string('channel');
            $t->string('recipient');
            $t->text('body');
            $t->string('state')->default('pending');
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('whatsapp_templates', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->string('channel')->nullable();
            $t->text('body');
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('whatsapp_messages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('template_id')->nullable()->constrained('whatsapp_templates')->nullOnDelete();
            $t->string('recipient');
            $t->text('body');
            $t->string('state')->default('pending');
            $t->text('error')->nullable();
            $t->timestamps();
        });

        Schema::create('feedback_forms', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('type');
            $t->json('questions')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('feedbacks', function (Blueprint $t) {
            $t->id();
            $t->foreignId('form_id')->nullable()->constrained('feedback_forms')->nullOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->integer('rating')->nullable();
            $t->text('comment')->nullable();
            $t->string('state')->default('submitted');
            $t->timestamps();
        });

        Schema::create('feedback_responses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('feedback_form_id')->constrained('feedback_forms')->cascadeOnDelete();
            $t->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $t->json('answers')->nullable();
            $t->timestamps();
        });

        Schema::create('mobile_app_configs', function (Blueprint $t) {
            $t->id();
            $t->string('config_key')->unique();
            $t->string('label')->nullable();
            $t->json('value')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('devices', function (Blueprint $t) {
            $t->id();
            $t->string('device_token')->unique();
            $t->string('platform')->nullable();
            $t->string('model')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('push_tokens', function (Blueprint $t) {
            $t->id();
            $t->foreignId('device_id')->nullable()->constrained('devices')->cascadeOnDelete();
            $t->string('token')->unique();
            $t->string('provider')->default('fcm');
            $t->boolean('active')->default(true);
            $t->timestamps();
        });

        Schema::create('activities', function (Blueprint $t) {
            $t->id();
            $t->morphs('subject');
            $t->string('type')->default('note');
            $t->text('body');
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        Schema::create('sequences', function (Blueprint $t) {
            $t->id();
            $t->string('name')->unique();
            $t->string('prefix')->nullable();
            $t->integer('next')->default(1);
            $t->integer('padding')->default(5);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sequences');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('push_tokens');
        Schema::dropIfExists('devices');
        Schema::dropIfExists('mobile_app_configs');
        Schema::dropIfExists('feedback_responses');
        Schema::dropIfExists('feedbacks');
        Schema::dropIfExists('feedback_forms');
        Schema::dropIfExists('whatsapp_messages');
        Schema::dropIfExists('whatsapp_templates');
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('api_users');
    }
};
