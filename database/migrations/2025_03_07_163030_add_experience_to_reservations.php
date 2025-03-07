<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Experiences\Experience::class)->nullable()->after('house_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('house_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['experience_id']);
            $table->dropColumn('experience_id');
            $table->unsignedBigInteger('house_id')->nullable(false)->change();
        });
    }
};
