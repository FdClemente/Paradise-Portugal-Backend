<?php

namespace App\Exceptions\Api\User;

use Illuminate\Http\Response;

class EmailAlreadyTaken extends \Exception
{
    public function __construct(string $message = 'exceptions.user.email_already_taken')
    {
        parent::__construct($message);
    }

    public function getStatus(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }
}
