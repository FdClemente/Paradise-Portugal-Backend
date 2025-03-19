<?php

namespace App\Http\Requests\Api\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNameRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'fist_name' => 'required|string',
            'last_name' => 'required|string',
        ];
    }
}
