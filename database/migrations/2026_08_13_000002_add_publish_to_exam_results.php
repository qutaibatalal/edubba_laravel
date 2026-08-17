<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_results', function (Blueprint $t) {
            $t->timestamp('published_at')->nullable()->after('result');
        });

        Schema::table('marksheets', function (Blueprint $t) {
            $t->timestamp('finalized_at')->nullable()->after('state');
        });
    }

    public function down(): void
    {
        Schema::table('exam_results', function (Blueprint $t) {
            $t->dropColumn('published_at');
        });

        Schema::table('marksheets', function (Blueprint $t) {
            $t->dropColumn('finalized_at');
        });
    }
};
