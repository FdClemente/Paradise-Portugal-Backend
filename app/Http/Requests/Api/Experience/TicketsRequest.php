<?php

namespace App\Http\Requests\Api\Experience;

use Illuminate\Foundation\Http\FormRequest;

class TicketsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ];
    }
}
