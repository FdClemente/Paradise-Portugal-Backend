<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;

class UserController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        return ApiSuccessResponse::make($user);
    }
}
