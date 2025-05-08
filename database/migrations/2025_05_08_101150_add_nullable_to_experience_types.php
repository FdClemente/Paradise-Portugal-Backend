<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('experience_types', function (Blueprint $table) {
            $table->text('description')->default("")->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('experience_types', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change();
        });
    }
};
