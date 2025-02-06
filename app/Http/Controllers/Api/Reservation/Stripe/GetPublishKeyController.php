<?php

namespace App\Http\Controllers\Api\Reservation\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiSuccessResponse;

class GetPublishKeyController extends Controller
{
    public function __invoke()
    {
        return ApiSuccessResponse::make(['publish_key' => config('services.stripe.publishable')]);
    }
}
