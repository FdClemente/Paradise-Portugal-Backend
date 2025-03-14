<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Settings\LegalSettings;

class LegalController extends Controller
{
    public function __invoke(
        string $type,
    )
    {
        $settings = app(LegalSettings::class);

        $legalText = match ($type) {
            'privacy_policy' => $settings->privacy_policy,
            'cancellation_policy' => $settings->cancellation_policy,
            'terms_and_conditions' => $settings->terms_and_conditions,
        };

        $text = isset($legalText[app()->getLocale()]) ? $legalText[app()->getLocale()] : $legalText[app()->getFallbackLocale()];

        return ApiSuccessResponse::make([
            'text' => $text,
        ]);
    }
}
