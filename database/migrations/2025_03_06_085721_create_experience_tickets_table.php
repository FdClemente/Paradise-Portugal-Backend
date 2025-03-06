<?php

use App\Models\Experiences\Experience;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('experience_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Experience::class)->constrained('experiences');
            $table->string('name');
            $table->string('type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experience_tickets');
    }
};
