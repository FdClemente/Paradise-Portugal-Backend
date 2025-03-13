<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets_reservations', function (Blueprint $table) {
            $table->integer('price')->nullable()->after('date');
        });
    }

    public function down(): void
    {
        Schema::table('tickets_reservations', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};
