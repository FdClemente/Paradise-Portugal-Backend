<?php

namespace App\Models\Experiences;

use App\Enum\Experience\TicketType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExperienceTicket extends Model
{
    protected $fillable = [
        'experience_id',
        'name',
        'type',
    ];

    protected $casts = [
        'type' => TicketType::class
    ];

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }

    public function prices()
    {
        return $this->hasMany(ExperiencePrice::class);
    }
}
