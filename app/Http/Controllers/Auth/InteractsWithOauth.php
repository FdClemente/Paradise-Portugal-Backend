<?php

namespace App\Http\Controllers\Auth;

use App\Enum\LoginProviders;
use App\Exceptions\Api\User\InvalidOauthProvider;
use App\Models\Auth\LoginProvider;
use App\Models\User;

trait InteractsWithOauth
{
    /**
     * @throws InvalidOauthProvider
     */
    private function findOrValidateLoginProvider($socialiteUser, LoginProviders $providers): User
    {
        $loginProvider = LoginProvider::where('provider_id', $socialiteUser->getId())
            ->where('provider', $providers)
            ->first();

        if (!$loginProvider) {
            throw new InvalidOauthProvider();
        }

        return $loginProvider->user;
    }

    private function createUserFromSocialiteUser($socialiteUser, LoginProviders $providers): User
    {
        [$firstName, $lastName] = $this->extractNameParts($socialiteUser->getName());

        $createdUser = new User();
        $createdUser->first_name = $firstName;
        $createdUser->last_name = $lastName;
        $createdUser->email = $socialiteUser->getEmail();
        $createdUser->email_verified_at = now();
        $createdUser->password = \Hash::make(\Str::random(20));
        $createdUser->save();

        $createdUser->addMediaFromUrl($socialiteUser->getAvatar())->toMediaCollection('avatar');
        $createdUser->loginProviders()->create([
            'provider' => $providers,
            'provider_id' => $socialiteUser->getId(),
        ]);

        return $createdUser;
    }

    private function extractNameParts(string $fullName): array
    {
        $parts = explode(' ', $fullName);
        return [$parts[0], $parts[1] ?? ''];
    }
}
