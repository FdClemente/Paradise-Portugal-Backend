<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'auth'], function () {
    Route::get('/password-reset/{token}', [\App\Http\Controllers\Auth\PasswordResetController::class, 'index'])->name('password.reset');
    Route::post('/password-reset', [\App\Http\Controllers\Auth\PasswordResetController::class, 'store'])->name('password.reset.store');
});
