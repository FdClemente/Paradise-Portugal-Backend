<?php

use App\Http\Middleware\ETagMiddleware;
use App\Http\Middleware\SetAppLanguageMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware([SetAppLanguageMiddleware::class, ETagMiddleware::class])->group(function () {
    Route::get('/me', App\Http\Controllers\Api\UserController::class)->middleware('auth:sanctum');
    Route::delete('/logout', App\Http\Controllers\Api\Auth\LogoutController::class)->middleware('auth:sanctum');

    Route::group(['prefix' => '/map'], function () {
        Route::post('/', App\Http\Controllers\Api\MapController::class);
        Route::get('/search', App\Http\Controllers\Api\Map\SearchPoiController::class);
        Route::get('/poi-types', App\Http\Controllers\Api\Map\PoiTypesController::class);
    });

    Route::group(['prefix' => '/user', 'middleware' => 'auth:sanctum'], function () {
        Route::post('addNotificationDevice', App\Http\Controllers\Api\User\AddDeviceNotificationController::class);
        Route::post('delete', App\Http\Controllers\Api\Auth\DeleteAccountController::class);
    });


    Route::resource('houses', App\Http\Controllers\Api\HouseController::class);
    Route::get('/houses/{house}/static_map', [App\Http\Controllers\Api\MapImageController::class, 'house']);
    Route::get('/houses/{house}/booking-dates', App\Http\Controllers\Api\House\GetDisableDatesAndPriceController::class);

    Route::post('/rating', App\Http\Controllers\Api\RatingController::class)->middleware('auth:sanctum');
    Route::get('/rating', App\Http\Controllers\Api\CheckRatingController::class);

    Route::group(['prefix' => '/experience'], function () {
        Route::post('/tickets/price', App\Http\Controllers\Api\Experiences\TicketPriceController::class);
        Route::post('/{experience}/tickets', App\Http\Controllers\Api\Experiences\TicketsController::class);
        Route::get('/search', App\Http\Controllers\Api\Experiences\SearchExperienceController::class);

        Route::get('/{experience}/static_map', [App\Http\Controllers\Api\MapImageController::class, 'experience']);
    });
    Route::get('experiences-types', App\Http\Controllers\Api\Experiences\ExperienceTypeController::class);
    Route::resource('/experience', App\Http\Controllers\Api\Experiences\ExperienceController::class)->only('index', 'show');


    Route::resource('/wishlist', \App\Http\Controllers\Api\WishlistController::class)
        ->only('index', 'store', 'destroy', 'show')
        ->middleware('auth:sanctum');

    Route::post('/wishlist/{wishlist}/attach', [\App\Http\Controllers\Api\WishlistController::class, 'attach'])->middleware('auth:sanctum');
    Route::post('/wishlist/detach', [\App\Http\Controllers\Api\WishlistController::class, 'detach'])->middleware('auth:sanctum');

    Route::post('/reservation', App\Http\Controllers\Api\Reservation\CalculateTotalController::class);
    Route::group(['prefix' => 'reservation'], function () {
        Route::get('/', App\Http\Controllers\Api\Reservation\UpcomingReservationController::class)->middleware('auth:sanctum');
        Route::get('/check-rating', App\Http\Controllers\Api\Reservation\CheckNeedShowRatingController::class)->middleware('auth:sanctum');
        Route::get('/check-rating/{reservation}', App\Http\Controllers\Api\Reservation\DemiseRattingController::class)->middleware('auth:sanctum');
        Route::get('stripe-publishable-key', App\Http\Controllers\Api\Reservation\Stripe\GetPublishKeyController::class);
        Route::post('/cancel', App\Http\Controllers\Api\Reservation\CancelReservationController::class);
        Route::get('/my-payments', App\Http\Controllers\Api\Reservation\MyPaymentsController::class);
        Route::post('/attach', App\Http\Controllers\Api\Reservation\AttachReservationController::class);
        Route::get('/{reservation}', App\Http\Controllers\Api\Reservation\ReservationDetailsController::class)->middleware('auth:sanctum');


        Route::post('create-payment', App\Http\Controllers\Api\Reservation\Stripe\PaymentController::class);
        Route::post('cancel-payment', App\Http\Controllers\Api\Reservation\CancelPaymentController::class);
        Route::post('payment-complete', App\Http\Controllers\Api\Reservation\Stripe\PaymentCompleteController::class);
    });

    Route::group(['prefix' => 'profile', 'middleware' => 'auth:sanctum'], function (){
        Route::post('setImage', App\Http\Controllers\Api\Profile\UploadImageController::class);
        Route::post('name', App\Http\Controllers\Api\Profile\UpdateNameController::class);
        Route::post('date-birthday', App\Http\Controllers\Api\Profile\UpdateDateBirthdayController::class);
        Route::post('email', App\Http\Controllers\Api\Profile\UpdateEmailController::class);
        Route::post('address', App\Http\Controllers\Api\Profile\UpdateAddressController::class);
        Route::post('phone-number', App\Http\Controllers\Api\Profile\UpdatePhoneNumberController::class);
        Route::post('notifications', App\Http\Controllers\Api\Profile\NotificationsController::class);
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

        Route::group(['prefix' => '/forgot-password'], function () {
            Route::post('email',  App\Http\Controllers\Api\Auth\PasswordReset\ForgotPasswordController::class);
            Route::post('code/check', App\Http\Controllers\Api\Auth\PasswordReset\CodeCheckController::class);
            Route::post('reset', App\Http\Controllers\Api\Auth\PasswordReset\ResetPasswordController::class);
        });

        Route::group(['middleware' => 'auth:sanctum'], function (){
            Route::post('/update-password', App\Http\Controllers\Api\Auth\UpdatePasswordController::class);
        });

        Route::get('/email/verify/{id}/{hash}', App\Http\Controllers\Api\Auth\VerifyEmailController::class)->name('verification.verify');
    });

    Route::group(['prefix' => '/cancellation', 'middleware' => 'auth:sanctum'], function () {
        Route::get('/motives', \App\Http\Controllers\Api\CancellationMotiveController::class);
    });

    Route::get('/legal/{type}', App\Http\Controllers\Api\LegalController::class)
        ->where('type', 'privacy_policy|cancellation_policy|terms_and_conditions');

    Route::group(['prefix' => 'guest-paradise/reservation', 'middleware' => 'auth:sanctum'], function (){
        Route::post('/', [App\Http\Controllers\GuestParadise\ReservationSyncController::class, 'store']);
        Route::delete('/{externalId}', [App\Http\Controllers\GuestParadise\ReservationSyncController::class, 'destroy']);
    });
});
