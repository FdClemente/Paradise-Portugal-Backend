<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('house_types', function (Blueprint $table) {
            $table->string('wp_category')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('house_types', function (Blueprint $table) {
            $table->dropColumn('wp_category');
        });
    }
};
