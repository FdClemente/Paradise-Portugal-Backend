<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;

class SendVerificationEmailController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return ApiSuccessResponse::make();
    }
}
