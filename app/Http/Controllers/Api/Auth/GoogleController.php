<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enum\LoginProviders;
use App\Http\Controllers\Auth\InteractsWithOauth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\GoogleRequest;
use App\Http\Responses\Api\ApiErrorResponse;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\User;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    use InteractsWithOauth;

    public function __invoke(GoogleRequest $request)
    {
        try {
            $email = $request->input('user.email');
            $googleId = $request->input('user.id');
            if (User::where('email', $email)->exists()) {
                $user = $this->findOrValidateLoginProvider($googleId, LoginProviders::GOOGLE);

            } else {
                $firstName = $request->input('user.givenName');
                $lastName = $request->input('user.familyName');
                $photo = $request->input('user.photo');
                $user = $this->createUserFromSocialiteUser($googleId, $firstName, $lastName, $email, $photo, LoginProviders::GOOGLE);

            }

            $deviceName = $request->get('deviceName');
            $loginService = new LoginService($user, $deviceName);

            return ApiSuccessResponse::make($loginService->login());
        } catch (\Exception $e) {
            return new ApiErrorResponse($e);
        }
    }

}
