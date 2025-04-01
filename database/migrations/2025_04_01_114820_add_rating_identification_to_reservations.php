<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->boolean('house_rated')->default(false)->after('house_id');
            $table->boolean('experience_rated')->default(false)->after('experience_id');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('house_rated');
            $table->dropColumn('experience_rated');
        });
    }
};
