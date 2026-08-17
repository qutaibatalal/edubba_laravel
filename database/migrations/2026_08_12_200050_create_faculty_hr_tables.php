<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty_hr', function (Blueprint $t) {
            $t->id();
            $t->foreignId('faculty_id')->constrained('faculties')->cascadeOnDelete();
            $t->string('employee_type')->nullable();
            $t->date('contract_start')->nullable();
            $t->date('contract_end')->nullable();
            $t->decimal('salary', 12, 2)->default(0);
            $t->string('bank_name')->nullable();
            $t->string('bank_account')->nullable();
            $t->string('tin')->nullable();
            $t->json('documents')->nullable();
            $t->timestamps();
        });

        Schema::create('employees', function (Blueprint $t) {
            $t->id();
            $t->string('employee_code')->nullable();
            $t->string('name');
            $t->string('gender')->nullable();
            $t->date('birth_date')->nullable();
            $t->string('phone')->nullable();
            $t->string('email')->nullable();
            $t->text('address')->nullable();
            $t->string('job_title')->nullable();
            $t->string('department')->nullable();
            $t->date('join_date')->nullable();
            $t->date('leaving_date')->nullable();
            $t->decimal('salary', 12, 2)->default(0);
            $t->string('state')->default('draft');
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('faculty_hr');
    }
};
