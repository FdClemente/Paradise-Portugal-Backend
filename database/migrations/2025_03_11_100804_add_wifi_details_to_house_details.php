<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('house_details', function (Blueprint $table) {
            $table->string('wifi_ssid')->nullable()->after('check_in_time');
            $table->string('wifi_password')->nullable()->after('wifi_ssid');
        });
    }

    public function down(): void
    {
        Schema::table('house_details', function (Blueprint $table) {
            $table->dropColumn('wifi_ssid');
            $table->dropColumn('wifi_password');
        });
    }
};
