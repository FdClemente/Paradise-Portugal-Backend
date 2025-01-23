<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('house_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\House::class)->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->integer('price');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('house_prices');
    }
};
