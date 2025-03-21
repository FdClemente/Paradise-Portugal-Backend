<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\UpdatePhoneNumberRequest;
use App\Http\Responses\Api\ApiSuccessResponse;

class UpdatePhoneNumberController extends Controller
{
    public function __invoke(UpdatePhoneNumberRequest $request)
    {
        $user = auth()->user();

        $user->phone_number = $request->get('phoneNumber');
        $user->country_phone = $request->get('countryCode');
        $user->save();
        return ApiSuccessResponse::make();
    }
}
