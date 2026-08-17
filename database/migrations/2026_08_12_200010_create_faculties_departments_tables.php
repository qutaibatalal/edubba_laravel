<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculties', function (Blueprint $t) {
            $t->id();
            $t->string('faculty_code')->nullable();
            $t->string('name');
            $t->string('middle_name')->nullable();
            $t->string('last_name')->nullable();
            $t->date('birth_date')->nullable();
            $t->string('gender')->nullable();
            $t->string('marital_status')->nullable();
            $t->string('national_id')->nullable();
            $t->string('phone')->nullable();
            $t->string('mobile')->nullable();
            $t->string('email')->nullable();
            $t->text('address')->nullable();
            $t->string('qualification')->nullable();
            $t->string('specialization')->nullable();
            $t->date('join_date')->nullable();
            $t->date('leaving_date')->nullable();
            $t->foreignId('department_id')->nullable();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('photo')->nullable();
            $t->string('state')->default('draft');
            $t->boolean('active')->default(true);
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->softDeletes();
            $t->unique('faculty_code');
        });

        Schema::create('departments', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('code')->nullable();
            $t->text('description')->nullable();
            $t->foreignId('head_faculty_id')->nullable()->constrained('faculties')->nullOnDelete();
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::table('faculties', function (Blueprint $t) {
            $t->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('faculties', function (Blueprint $t) {
            $t->dropForeign(['department_id']);
        });
        Schema::dropIfExists('departments');
        Schema::dropIfExists('faculties');
    }
};
