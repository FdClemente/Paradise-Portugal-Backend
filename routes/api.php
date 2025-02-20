<?php

use App\Http\Middleware\ETagMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(ETagMiddleware::class)->group(function () {
    Route::get('/me', App\Http\Controllers\Api\UserController::class)->middleware('auth:sanctum');
    Route::delete('/logout', App\Http\Controllers\Api\Auth\LogoutController::class)->middleware('auth:sanctum');

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

    Route::group(['prefix' => '/auth'], function () {
        Route::post('/login', App\Http\Controllers\Api\Auth\LoginController::class);
        Route::post('/signup', App\Http\Controllers\Api\Auth\SignUpController::class);
        Route::post('/signup/verificationEmail', App\Http\Controllers\Api\Auth\SendVerificationEmailController::class)->middleware('auth:sanctum');
        Route::post('/validate-email', App\Http\Controllers\Api\Auth\ValidateEmailController::class);

        Route::group(['prefix' => '/oauth'], function () {
            Route::post('facebook', App\Http\Controllers\Api\Auth\FacebookController::class);
            Route::post('google', App\Http\Controllers\Api\Auth\GoogleController::class);
            Route::post('apple', App\Http\Controllers\Api\Auth\AppleController::class);
        });

        Route::group(['middleware' => 'auth:sanctum'], function (){
            Route::post('/update-password', App\Http\Controllers\Api\Auth\UpdatePasswordController::class);
        });

        Route::get('/email/verify/{id}/{hash}', App\Http\Controllers\Api\Auth\VerifyEmailController::class)->name('verification.verify');
    });

});


