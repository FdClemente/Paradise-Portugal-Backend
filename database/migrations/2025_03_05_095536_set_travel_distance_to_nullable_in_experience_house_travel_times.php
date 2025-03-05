<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('experience_house_travel_times', function (Blueprint $table) {
            $table->string('travel_distance')->nullable()->change();
            $table->float('travel_time')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('experience_house_travel_times', function (Blueprint $table) {
            $table->string('travel_distance')->nullable(false)->change();
            $table->float('travel_time')->nullable(false)->change();
        });
    }
};
