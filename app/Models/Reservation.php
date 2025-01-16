<?php

namespace App\Models;

use App\Enum\ReservationStatusEnum;
use Guava\Calendar\Contracts\Eventable;
use Guava\Calendar\ValueObjects\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model implements Eventable
{
    use SoftDeletes;

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => ReservationStatusEnum::class
    ];

    protected $fillable = [
        'user_id',
        'house_id',
        'check_in_date',
        'check_out_date',
        'num_guests',
        'status',
        'reservation_code',
    ];
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->whereHas('roles', function ($query) {
            $query->where('role', 'customer');
        });
    }

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function toEvent(): Event|array {
        return Event::make($this)
            ->title($this->customer->name)
            ->start($this->check_in_date)
            ->end($this->check_out_date);
    }
}
