<?php

namespace App\Http\Requests\Api\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class PaymentCompleteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'paymentIntent' => 'required|string',
            'deviceName' => 'required|string'
        ];
    }
}
