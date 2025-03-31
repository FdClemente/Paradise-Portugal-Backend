<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RatingRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Rating;

class RatingController extends Controller
{
    public function __invoke(RatingRequest $request)
    {
        $rateable = $request->rateable();

        $rating = Rating::create([
            'rateable_id' => $rateable->id,
            'rateable_type' => get_class($rateable),
            'user_id' => auth()->user()->id,
            'score' => $request->score,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        return ApiSuccessResponse::make($rating);
    }
}
