<?php

use App\Http\Middleware\ETagMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(ETagMiddleware::class)->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    Route::post('/map', App\Http\Controllers\Api\MapController::class);

    Route::resource('houses', App\Http\Controllers\Api\HouseController::class);
    Route::get('/houses/{house}/static_map', App\Http\Controllers\Api\MapImageController::class);
});

