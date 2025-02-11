<?php

use App\Http\Middleware\ETagMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(ETagMiddleware::class)->group(function () {
    Route::get('/me', \App\Http\Controllers\Api\UserController::class)->middleware('auth:sanctum');

    Route::post('/map', App\Http\Controllers\Api\MapController::class);

    Route::resource('houses', App\Http\Controllers\Api\HouseController::class);
    Route::get('/houses/{house}/static_map', App\Http\Controllers\Api\MapImageController::class);
    Route::get('/houses/{house}/booking-dates', App\Http\Controllers\Api\House\GetDisableDatesAndPriceController::class);

    Route::post('/reservation', App\Http\Controllers\Api\Reservation\CalculateTotalController::class);

    Route::group(['prefix' => 'reservation'], function () {
        Route::get('stripe-publishable-key', App\Http\Controllers\Api\Reservation\Stripe\GetPublishKeyController::class);

        Route::post('create-payment', App\Http\Controllers\Api\Reservation\Stripe\PaymentController::class);
        Route::post('payment-complete', App\Http\Controllers\Api\Reservation\Stripe\PaymentCompleteController::class);
    });

    Route::post('/auth/login', App\Http\Controllers\Api\Auth\LoginController::class);
    Route::post('/auth/validate-email', App\Http\Controllers\Api\Auth\ValidateEmailController::class);
    Route::group(['middleware' => 'auth:sanctum', 'prefix' => '/auth'], function (){
        Route::post('/update-password', App\Http\Controllers\Api\Auth\UpdatePasswordController::class);
    });
});


