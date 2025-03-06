<?php

use App\Models\Experiences\Experience;
use App\Models\Experiences\ExperienceTicket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('experience_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ExperienceTicket::class)->constrained('experience_tickets');
            $table->string('ticket_type');
            $table->integer('price');
            $table->text('specific_dates');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('experience_prices');
    }
};
