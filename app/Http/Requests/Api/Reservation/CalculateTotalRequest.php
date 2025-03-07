<?php

namespace App\Http\Requests\Api\Reservation;

use App\Models\Experiences\Experience;
use App\Models\House\House;
use Illuminate\Foundation\Http\FormRequest;

class CalculateTotalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'house_id' => 'nullable|integer|exists:houses,id',
            'experience_id' => 'nullable|integer|exists:experiences,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'adults' => 'required|integer',
            'children' => 'nullable|integer',
            'babies' => 'nullable|integer',
            'tickets' => 'nullable|array',
        ];
    }

    public function house(): House|null
    {
        return House::find($this->get('house_id'));
    }

    public function experience(): Experience|null
    {
        return Experience::find($this->get('experience_id'));
    }
}
