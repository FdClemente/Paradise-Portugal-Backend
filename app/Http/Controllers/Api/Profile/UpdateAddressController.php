<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ManageAddress;
use App\Http\Requests\Api\Profile\UpdateAddressRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\Geocoder\Facades\Geocoder;

class UpdateAddressController extends Controller
{
    use ManageAddress;
    public function __invoke(UpdateAddressRequest $request)
    {
        $addressDetails = $this->mapAddress($request->all());

        $user = auth()->user();
        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            $addressDetails
        );
        $user->country = $addressDetails['country'];
        $user->save();

        return ApiSuccessResponse::make();
    }

}
