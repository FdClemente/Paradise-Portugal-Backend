<?php

namespace App\Enum;

enum LoginProviders:string
{
    case GOOGLE = 'google';
    case FACEBOOK = 'facebook';
}
