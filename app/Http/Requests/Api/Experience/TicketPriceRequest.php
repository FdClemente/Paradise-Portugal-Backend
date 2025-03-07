<?php

namespace App\Http\Requests\Api\Experience;

use Illuminate\Foundation\Http\FormRequest;

class TicketPriceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tickets.*' => 'array',
            'tickets.*.*' => 'array',
            'tickets.*.*.price_id' => 'required|integer|exists:experience_prices,id',
            'tickets.*.*.tickets' => 'required|integer|min:0',
            'tickets.*.*.type' => 'required|string'
        ];
    }
}
