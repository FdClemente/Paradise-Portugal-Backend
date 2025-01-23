<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('house_price_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\House\HousePrices::class)->constrained()->cascadeOnDelete();
            $table->integer('min_days_booking')->nullable();
            $table->integer('extra_price_per_guest')->nullable();
            $table->integer('price_per_weekend')->nullable();
            $table->integer('checkin_change_over')->nullable();
            $table->integer('checkin_checkout_change_over')->nullable();
            $table->integer('price_per_month')->nullable();
            $table->integer('price_per_week')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('house_price_details');
    }
};
