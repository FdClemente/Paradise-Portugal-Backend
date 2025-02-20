<?php

namespace App\Http\Controllers\Api\Auth;

use App\Exceptions\Api\User\EmailAlreadyTaken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\SignUpRequest;
use App\Http\Responses\Api\ApiErrorResponse;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\User;
use App\Services\Auth\LoginService;

class SignUpController extends Controller
{
    public function __invoke(SignUpRequest $request)
    {
        try {
            $data = $request->validated();
            if (User::where('email', $data['email'])->exists()) {
                throw new EmailAlreadyTaken();
            }
            $name = $request->get('name');
            $first_name = explode(' ', $name)[0];

            $last_name = implode(' ', array_slice(explode(' ', $name), 1));


            $user = User::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                ...$data
            ]);
            $user->save();
            $user->sendEmailVerificationNotification();

            $deviceName = $request->get('deviceName');
            $loginService = new LoginService($user, $deviceName);

            return ApiSuccessResponse::make($loginService->login());
        }catch (\Exception $e) {
            return new ApiErrorResponse($e);
        }

    }
}
