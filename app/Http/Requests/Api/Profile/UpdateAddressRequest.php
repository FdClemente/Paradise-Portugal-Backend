<?php

namespace App\Http\Requests\Api\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'addressLine1' => 'required|string',
            'addressLine2' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'postalCode' => 'required|string',
            'country' => 'nullable|string',
        ];
    }
}
