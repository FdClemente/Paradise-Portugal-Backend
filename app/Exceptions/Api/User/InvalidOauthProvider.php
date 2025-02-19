<?php

namespace App\Exceptions\Api\User;

use Illuminate\Http\Response;

class InvalidOauthProvider extends \Exception
{
    public function __construct(string $message = 'exceptions.user.invalidOauthProvider')
    {
        parent::__construct($message);
    }

    public function getStatus(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
