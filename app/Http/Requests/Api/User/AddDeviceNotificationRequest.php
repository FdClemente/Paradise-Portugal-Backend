<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use NotificationChannels\Expo\ExpoPushToken;

class AddDeviceNotificationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'device' => 'required|string',
            'token' => ['required', ExpoPushToken::rule()]
        ];
    }
}
