<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\NotificationRequest;
use App\Http\Responses\Api\ApiSuccessResponse;

class NotificationsController extends Controller
{
    public function __invoke(NotificationRequest $request)
    {
        $user = auth()->user();
        $user->allow_remainders_notifications = $request->get('allowRemainderNotification');
        $user->allow_marketing_notifications = $request->get('allowMarketingNotification');
        $user->save();
        return ApiSuccessResponse::make();
    }
}
