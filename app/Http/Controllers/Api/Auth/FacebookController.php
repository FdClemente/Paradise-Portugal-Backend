<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enum\LoginProviders;
use App\Http\Controllers\Auth\InteractsWithOauth;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiErrorResponse;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\User;
use App\Services\Auth\LoginService;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    use InteractsWithOauth;
    public function __invoke(Request $request)
    {
        try {
            $socialiteUser = Socialite::with('facebook')->userFromToken($request['accessToken']);
            $email = $socialiteUser->getEmail();

            if (User::where('email', $email)->exists()) {
                $user = $this->findOrValidateLoginProvider($socialiteUser, LoginProviders::FACEBOOK);
            } else {
                $user = $this->createUserFromSocialiteUser($socialiteUser, LoginProviders::FACEBOOK);
            }

            $deviceName = $request->get('deviceName');
            $loginService = new LoginService($user, $deviceName);

            return ApiSuccessResponse::make($loginService->login());
        } catch (\Exception $e) {
            return new ApiErrorResponse($e);
        }
    }
}
