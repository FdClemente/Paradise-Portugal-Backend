<?php

namespace App\Http\Requests\Api\Reservation;

use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;

class CancelReservationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reservation' => 'required|exists:reservations,id',
            'motive' => 'required|exists:cancellation_motives,id'
        ];
    }

    public function reservation():Reservation
    {
        return Reservation::findOrFail($this->get('reservation'));
    }
}
