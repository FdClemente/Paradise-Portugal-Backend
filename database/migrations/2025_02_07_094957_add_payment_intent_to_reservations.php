<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('payment_intent')->nullable()->after('status');
            $table->string('payment_intent_secret')->nullable()->after('payment_intent');
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('ip')->nullable()->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('payment_intent');
            $table->dropColumn('payment_intent_secret');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->dropColumn('ip');
        });
    }
};
