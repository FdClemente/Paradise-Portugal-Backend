<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('house_details_highlights', function (Blueprint $table) {
            $table->boolean('show_in_card')->default(false)->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('house_details_highlights', function (Blueprint $table) {
            $table->dropColumn('show_in_card');
        });
    }
};
