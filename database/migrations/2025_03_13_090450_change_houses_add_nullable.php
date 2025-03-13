<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->double('airbnb_ratting')->nullable()->change();
            $table->double('booking_ratting')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->double('airbnb_ratting')->nullable(false)->change();
            $table->double('booking_ratting')->nullable(false)->change();
        });
    }
};
