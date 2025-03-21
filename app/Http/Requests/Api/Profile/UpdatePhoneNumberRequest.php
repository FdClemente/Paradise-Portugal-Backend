<?php

namespace App\Http\Requests\Api\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhoneNumberRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'countryCode' => 'required|string',
            'phoneNumber' => 'required|string',
        ];
    }
}
