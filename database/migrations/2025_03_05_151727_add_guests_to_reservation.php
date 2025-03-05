<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->integer('adults')->nullable()->after('num_guests');
            $table->integer('children')->nullable()->after('adults');
            $table->integer('babies')->nullable()->after('children');

            $table->dropColumn('num_guests');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('adults');
            $table->dropColumn('children');
            $table->dropColumn('babies');

            $table->integer('num_guests')->nullable()->after('price');
        });
    }
};
