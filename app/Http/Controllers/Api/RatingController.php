<?php

namespace App\Http\Controllers\Api;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RatingRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Rating;

class RatingController extends Controller
{
    public function __invoke(RatingRequest $request)
    {
        $rateable = $request->rateable();
        $customer = auth()->user();

        $reservation = $customer->reservations()
            ->where('status', ReservationStatusEnum::COMPLETED)
            ->when($request->get('type') == 'house', fn ($query) => $query->where('house_id', $rateable->id)->where('house_rated', 0))
            ->when($request->get('type') == 'experience', fn ($query) => $query->where('experience_id', $rateable->id)->where('experience_rated', 0))
            ->first();

        if (!$reservation){
            return ApiSuccessResponse::make(metaData:['message' => 'You have not completed any reservation'], statusCode: 422);
        }


        $rating = Rating::create([
            'rateable_id' => $rateable->id,
            'rateable_type' => get_class($rateable),
            'user_id' => $customer->id,
            'score' => $request->score,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        if ($request->get('type') == 'house'){
            $reservation->update(['house_rated' => 1]);
        }
        if ($request->get('type') == 'experience'){
            $reservation->update(['experience_rated' => 1]);
        }

        return ApiSuccessResponse::make($rating);
    }
}
