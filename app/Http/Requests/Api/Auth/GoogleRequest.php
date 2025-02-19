<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class GoogleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user.email' => 'required|email',
            'user.id' => 'required',
            'user.givenName' => 'required|string',
            'user.familyName' => 'required|string',
            'user.photo' => 'required|string',
            'deviceName' => 'required|string',
        ];
    }
}
