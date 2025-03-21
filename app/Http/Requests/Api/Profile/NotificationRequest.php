<?php

namespace App\Http\Requests\Api\Profile;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'allowMarketingNotification' => 'required|boolean',
            'allowRemainderNotification' => 'required|boolean',
        ];
    }
}
