<?php

namespace App\Http\Requests\Api\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class CancelPaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'payment_intent' => ['string', 'required']
        ];
    }
}
