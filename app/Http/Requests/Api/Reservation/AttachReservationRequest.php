<?php

namespace App\Http\Requests\Api\Reservation;

use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;

class AttachReservationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => 'required|max_digits:6'
        ];
    }

    public function reserve(): ?Reservation
    {
        return Reservation::where('reservation_code', $this->get('code'))
            ->whereDate('check_out_date', '>', now())
            ->whereNull('user_id')
            ->first();
    }
}
