<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('phone')->nullable()->after('email');
            $t->boolean('two_factor_enabled')->default(false)->after('password');
            $t->text('two_factor_secret')->nullable()->after('two_factor_enabled');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn(['phone', 'two_factor_enabled', 'two_factor_secret']);
        });
    }
};
