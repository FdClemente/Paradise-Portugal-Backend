<?php

namespace App\Http\Requests\Api\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDateBirthdayRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'date_of_birth' => 'required|date'
        ];
    }
}
