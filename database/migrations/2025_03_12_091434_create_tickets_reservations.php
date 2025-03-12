<?php

use App\Models\Reservation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tickets_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Reservation::class)->constrained('reservations');
            $table->foreignIdFor(\App\Models\Experiences\ExperiencePrice::class)->constrained('experience_prices');
            $table->date('date');
            $table->integer('tickets');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets_reservations');
    }
};
