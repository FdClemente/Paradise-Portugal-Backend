<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('poi_house_travel_time', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\House\House::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Pois\Poi::class)->constrained()->cascadeOnDelete();
            $table->integer('travel_time')->nullable();
            $table->string('travel_distance')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poi_house_travel_time');
    }
};
