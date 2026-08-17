<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notification_logs', function (Blueprint $t) {
            $t->foreignId('api_user_id')->nullable()->after('id')->constrained('api_users')->nullOnDelete();
            $t->timestamp('read_at')->nullable()->after('sent_at');
            $t->index(['api_user_id', 'read_at']);
        });

        Schema::table('push_tokens', function (Blueprint $t) {
            $t->foreignId('api_user_id')->nullable()->after('id')->constrained('api_users')->nullOnDelete();
            $t->string('device_type')->nullable()->after('provider');
        });
    }

    public function down(): void
    {
        Schema::table('notification_logs', function (Blueprint $t) {
            $t->dropIndex(['api_user_id', 'read_at']);
            $t->dropForeign(['api_user_id']);
            $t->dropColumn(['api_user_id', 'read_at']);
        });

        Schema::table('push_tokens', function (Blueprint $t) {
            $t->dropForeign(['api_user_id']);
            $t->dropColumn(['api_user_id', 'device_type']);
        });
    }
};
