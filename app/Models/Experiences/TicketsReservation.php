<?php

namespace App\Models\Experiences;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Number;

class TicketsReservation extends Model
{
    protected $fillable = [
        'reservation_id', 'experience_price_id', 'date', 'tickets','price'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function priceDetails()
    {
        return $this->belongsTo(ExperiencePrice::class, 'experience_price_id');
    }

    public function ticketPriceDescription(): Attribute
    {
        return Attribute::make(get: function (){
            return $this->tickets.' x '.Number::currency($this->price/100, 'EUR');
        });
    }
}
