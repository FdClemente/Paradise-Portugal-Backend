<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('house_disable_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\House::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Reservation::class)->nullable()->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('house_disable_dates');
    }
};
