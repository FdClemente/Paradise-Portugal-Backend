<?php

namespace App\Models\Experiences;

use App\Casts\House\PriceCast;
use App\Enum\Experience\TicketPriceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperiencePrice extends Model
{
    protected $fillable = [
        'experience_id',
        'experience_ticket_id',
        'ticket_type',
        'price',
    ];

    protected $casts = [
        'price' => PriceCast::class,
        'ticket_type' => TicketPriceType::class,
    ];

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }

    public function experienceTicket(): BelongsTo
    {
        return $this->belongsTo(ExperienceTicket::class);
    }
}
