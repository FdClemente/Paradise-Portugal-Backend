<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\HouseType::class)->after('description')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->dropForeign(['house_type_id']);
            $table->dropColumn('house_type_id');
        });
    }
};
