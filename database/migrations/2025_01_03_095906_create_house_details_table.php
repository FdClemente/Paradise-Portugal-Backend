<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('house_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\House\House::class)->constrained()->cascadeOnDelete();
            $table->float('area')->nullable();
            $table->integer('num_bedrooms')->nullable();
            $table->integer('num_bathrooms')->nullable();
            $table->time('check_in_time')->nullable();
            $table->time('check_out_time')->nullable();
            $table->boolean('private_bathroom');
            $table->boolean('private_entrance');
            $table->boolean('family_friendly');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('house_details');
    }
};
