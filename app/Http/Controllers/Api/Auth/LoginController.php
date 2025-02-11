<?php

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\Api\User\WrongCredentials;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Responses\Api\ApiErrorResponse;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\User;
use Exception;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        try {
            $customer = User::where('email', $request->get('email'))->firstOrFail();

            if (\Auth::attempt($request->only('email', 'password'))){
                $token = $customer->createToken('auth_token')->plainTextToken;

                return ApiSuccessResponse::make([
                    'token' => $token,
                ]);
            }else{
                throw new WrongCredentials();
            }
        }catch (Exception $exception){
            return new ApiErrorResponse($exception, $exception->getMessage(), 400);
        }


    }
}
