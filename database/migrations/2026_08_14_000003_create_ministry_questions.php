<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ministry question bank — past exam questions per subject/year.
        Schema::create('ministry_questions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $t->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $t->string('stage');          // e.g. "السادس العلمي"
            $t->string('question_type');  // mcq | essay | true_false | short
            $t->text('question');
            $t->json('options')->nullable();
            $t->text('answer')->nullable();
            $t->smallInteger('marks')->default(0);
            $t->integer('year')->nullable();
            $t->integer('session')->nullable(); // 1st / 2nd / 3rd session
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ministry_questions');
    }
};
