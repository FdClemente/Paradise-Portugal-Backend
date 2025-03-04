<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('experience_experience_service', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Experience::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Settings\ExperienceService::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experience_experience_service');
    }
};
