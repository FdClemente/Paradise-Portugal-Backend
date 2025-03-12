<?php

namespace App\Models\Experiences;

use Illuminate\Database\Eloquent\Model;

class TicketsReservation extends Model
{
    protected $fillable = [
        'reservation_id', 'experience_price_id', 'date', 'tickets',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function priceDetails()
    {
        return $this->belongsTo(ExperiencePrice::class, 'experience_price_id');
    }
}
