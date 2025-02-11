<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ValidateEmailRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\User;

class ValidateEmailController extends Controller
{
    public function __invoke(ValidateEmailRequest $request)
    {
        $email = $request->get('email');

        return ApiSuccessResponse::make(['exists' => User::where('email', $email)->exists()]);
    }
}
