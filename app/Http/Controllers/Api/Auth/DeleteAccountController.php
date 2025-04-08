<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enum\ReservationStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Responses\Api\ApiErrorResponse;
use App\Http\Responses\Api\ApiSuccessResponse;

class DeleteAccountController extends Controller
{
    public function __invoke()
    {
        try {
            \DB::beginTransaction();
            $user = auth()->user();

            if ($user->reservations()->whereIn('status', ReservationStatusEnum::getActiveReservations())->exists()) {
                throw new \Exception('You can not delete your account because you have active reservations');
            }
            $user->reservations()->update(['user_id' => null]);
            $user->ratings()->delete();
            $user->devices()->delete();
            $user->address()->delete();
            $user->loginProviders()->delete();
            $user->roles()->delete();
            $user->wishlist()->delete();


            $user->delete();

            \DB::commit();

            return ApiSuccessResponse::make();

        }catch (\Exception $e){
            \DB::rollBack();
            return new ApiErrorResponse($e);
        }
    }
}
