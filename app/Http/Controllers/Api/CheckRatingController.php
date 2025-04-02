<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ListRatingRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class CheckRatingController extends Controller
{
    public function __invoke(ListRatingRequest $request)
    {
        $model = $request->rateable();

        $ratings = $model->ratings;

        if(!$ratings){
            return ApiSuccessResponse::make();
        }

        $ratings = $ratings->transform(function (Rating $rating) {
            $user = User::find($rating['user_id']);
            return [
                'id' => $rating->id,
                'comment' => $rating->comment,
                'score' => $rating->score,
                'profile_picture' => $user?->avatar_url,
                'name' => $user->first_name,
                'date' => $rating->created_at->fromNow(),
            ];
        });

        return ApiSuccessResponse::make([
            'ratings' => $ratings,
            'count' => $ratings->count(),
            'average' => $ratings->avg('score'),
        ]);
    }
}
