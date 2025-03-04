<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->integer('adult_price')->nullable()->after('description');
            $table->integer('child_price')->nullable()->after('adult_price');
            $table->text('additional_info')->nullable()->after('child_price');
        });
    }

    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn('adult_price');
            $table->dropColumn('child_price');
            $table->dropColumn('additional_info');
        });
    }
};
