<?php

namespace App\Http\Requests\Api\Auth\Oauth;

use Illuminate\Foundation\Http\FormRequest;

class FacebookRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'accessToken' => 'required|string',
            'deviceName' => 'required|string'
        ];
    }
}
