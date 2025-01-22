<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->string('wp_id')->nullable()->after('id');
            $table->integer('default_price')->nullable()->after('name');
            $table->string('city')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->dropColumn('wp_id');
            $table->dropColumn('default_price');
            $table->string('city')->change();
        });
    }
};
