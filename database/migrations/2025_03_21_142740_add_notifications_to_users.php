<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('allow_marketing_notifications')->default(true)->after('email_verified_at');
            $table->boolean('allow_remainders_notifications')->default(true)->after('allow_marketing_notifications');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('allow_marketing_notifications');
            $table->dropColumn('allow_remainders_notifications');
        });
    }
};
