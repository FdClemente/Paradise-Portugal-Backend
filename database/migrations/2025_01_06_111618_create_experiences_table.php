<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Settings\ExperienceType::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Settings\ExperiencePartner::class)->nullable()->constrained()->cascadeOnDelete();
            $table->json('name');
            $table->json('description');
            $table->integer('min_guests')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experiences');
    }
};
