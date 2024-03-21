<?php

use App\Http\Controllers\AuthOtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::name("auth.")->group(function () {
    Route::controller(AuthOtpController::class)->group(function () {
        Route::post('/verify-mobile', 'loginRequest')->name('login.mobile');
        Route::post('/verify-otp', 'verifyOtp')->name('login.otp');
        Route::post('/verify-web-otp', 'verifyWebOtp')->name('login.web.otp');
//        Route::post('/login-otp', [AuthOtpController::class, 'loginRequest'])->name('login.otp');
//        Route::post('/verify', [AuthOtpController::class, 'verifyOtp'])->name('verify.otp');
    });
});
