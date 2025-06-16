<?php

namespace App\Http\Requests\Api\Guest;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|integer',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date',
            'adults' => 'required|integer',
            'children' => 'nullable|integer',
            'house_id' => 'required'
        ];
    }
}
