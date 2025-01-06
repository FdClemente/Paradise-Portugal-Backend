<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('experience_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('street_name')->nullable();
            $table->string('street_number')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('zip')->nullable();
            $table->string('country');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('phone_number')->nullable();
            $table->string('email');

            $table->string('website')->nullable();
            $table->json('languages');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experience_partners');
    }
};
