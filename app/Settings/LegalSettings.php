<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class LegalSettings extends Settings
{
    public array $terms_and_conditions;
    public array $privacy_policy;
    public array $cancellation_policy;


    public static function group(): string
    {
        return 'legal';
    }
}
