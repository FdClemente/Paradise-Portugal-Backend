<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\AddDeviceNotificationRequest;
use App\Http\Responses\Api\ApiSuccessResponse;

class AddDeviceNotificationController extends Controller
{
    public function __invoke(AddDeviceNotificationRequest $request)
    {
        $user = auth('api')->user();
        $user->devices()->updateOrCreate([
            'device_name' => $request->get('device'),
            'expo_token' => $request->get('token'),
        ]);

        return ApiSuccessResponse::make();
    }
}
