<?php

namespace App\Http\Requests\Api\Reservation;

use App\Models\House;
use Illuminate\Foundation\Http\FormRequest;

class CalculateTotalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'house_id' => 'required|integer|exists:houses,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'num_guests' => 'required|integer',
        ];
    }

    public function house(): House
    {
        return House::findOrFail($this->get('house_id'));
    }
}
