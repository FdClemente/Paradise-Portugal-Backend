<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('experiences_availabilities', function (Blueprint $table) {
            $table->text('specific_dates')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('experiences_availabilities', function (Blueprint $table) {
            $table->text('specific_dates')->nullable(false)->change();
        });
    }
};
