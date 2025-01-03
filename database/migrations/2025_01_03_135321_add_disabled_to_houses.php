<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->boolean('is_disabled')->default(false)->after('description');
            $table->integer('house_id')->nullable()->after('is_disabled');
        });
    }

    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->dropColumn('is_disabled');
            $table->dropColumn('house_id');
        });
    }
};
