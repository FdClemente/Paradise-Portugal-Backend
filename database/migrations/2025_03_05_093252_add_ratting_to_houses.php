<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->float('booking_ratting')->default(0)->after('description');
            $table->float('airbnb_ratting')->default(0)->after('booking_ratting');
        });
    }

    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->dropColumn('booking_ratting');
            $table->dropColumn('airbnb_ratting');
        });
    }
};
