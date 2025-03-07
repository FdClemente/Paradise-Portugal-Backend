<?php

namespace App\Http\Controllers\Api\Experiences;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Experience\TicketPriceRequest;
use App\Http\Responses\Api\ApiSuccessResponse;
use App\Models\Experiences\ExperiencePrice;
use Illuminate\Http\Request;

class TicketPriceController extends Controller
{
    public function __invoke(TicketPriceRequest $request)
    {
        $total = 0;
        foreach ($request->get('tickets') as $key => $value) {
            foreach ($value as $ticket){
                $price = ExperiencePrice::find($ticket['price_id']);
                $total += ($price->getRawOriginal('price') * $ticket['tickets']);
            }
        }

        return ApiSuccessResponse::make([
            'total' => $total,
        ]);
    }
}

