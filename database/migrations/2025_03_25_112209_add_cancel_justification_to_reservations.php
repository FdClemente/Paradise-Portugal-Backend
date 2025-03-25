<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\CancellationMotive::class)->nullable()->after('status')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['cancellation_motive_id']);
            $table->dropColumn('cancellation_motive_id');
        });
    }
};
