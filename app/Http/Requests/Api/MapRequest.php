<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MapRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'zoom' => 'nullable|numeric',
            'query' => 'nullable|string',
            'height' => 'nullable|numeric',
            'width' => 'nullable|numeric',
        ];
    }
}
