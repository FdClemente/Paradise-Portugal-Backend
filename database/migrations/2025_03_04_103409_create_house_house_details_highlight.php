<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('house_house_details_highlight', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\House::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Settings\HouseDetailsHighlight::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('house_house_details_highlight');
    }
};
