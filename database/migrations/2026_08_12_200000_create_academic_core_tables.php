<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->date('date_start');
            $t->date('date_stop');
            $t->boolean('current')->default(false);
            $t->boolean('active')->default(true);
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->softDeletes();
            $t->unique(['name', 'deleted_at']);
        });

        Schema::create('terms', function (Blueprint $t) {
            $t->id();
            $t->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $t->string('name');
            $t->date('date_start');
            $t->date('date_stop');
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->softDeletes();
            $t->unique(['academic_year_id', 'name']);
        });

        Schema::create('programs', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('code')->nullable();
            $t->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $t->integer('duration_years')->nullable();
            $t->text('description')->nullable();
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('subjects', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('code')->nullable();
            $t->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $t->boolean('is_language')->default(false);
            $t->boolean('active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('terms');
        Schema::dropIfExists('academic_years');
    }
};
