<?php

use Illuminate\Support\Facades\Route;

Route::group([], function () {
    Route::get('/', function () {
        return redirect()->route('download');
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::get('/password-reset/{token}', [App\Http\Controllers\Auth\PasswordResetController::class, 'index'])->name('password.reset');
        Route::post('/password-reset', [App\Http\Controllers\Auth\PasswordResetController::class, 'store'])->name('password.reset.store');
    });

    Route::get('/download', function () {
        return view('download.app');
    })->name('download');

    Route::get('/privacy', [\App\Http\Controllers\Web\PrivacyController::class, 'index'])->name('download');

});
