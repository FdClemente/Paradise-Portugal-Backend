<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('poi_house_travel_time', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\House::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Pois\Poi::class)->constrained()->cascadeOnDelete();
            $table->integer('travel_time');
            $table->string('travel_distance');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poi_house_travel_time');
    }
};
