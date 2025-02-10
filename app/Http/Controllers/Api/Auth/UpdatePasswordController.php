<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UpdatePasswordRequest;
use App\Http\Responses\Api\ApiSuccessResponse;

class UpdatePasswordController extends Controller
{
    public function __invoke(UpdatePasswordRequest $request)
    {
        $user = auth()->user();

        $user->password = $request->get('password');
        $user->need_change_password = false;

        $user->save();

        return ApiSuccessResponse::make();
    }
}
