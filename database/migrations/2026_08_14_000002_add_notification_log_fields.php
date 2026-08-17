<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_logs', function (Blueprint $t) {
            $t->text('error')->nullable()->after('body');
            $t->timestamp('sent_at')->nullable()->after('state');
        });
    }

    public function down(): void
    {
        Schema::table('notification_logs', function (Blueprint $t) {
            $t->dropColumn(['error', 'sent_at']);
        });
    }
};
