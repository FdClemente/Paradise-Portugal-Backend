<?php

namespace App\Services\Auth;

use App\Models\User;

class LoginService
{
    public function __construct(private User $user, private string $deviceName)
    {
    }

    public function login(){
        \Auth::loginUsingId($this->user->id);
        $token = $this->user->createToken($this->deviceName)->plainTextToken;

        return [
            'token' => $token,
            'email' => $this->user->email,
        ];
    }
}
