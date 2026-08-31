<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register/request-otp', [AuthenticationController::class, 'requestRegistrationOtp'])
            ->middleware('throttle:10,1');

        Route::post('/register', [AuthenticationController::class, 'register'])
            ->middleware('throttle:10,1');

        Route::post('/login/request-otp', [AuthenticationController::class, 'requestLoginOtp'])
            ->middleware('throttle:10,1');

        Route::post('/login', [AuthenticationController::class, 'login'])
            ->middleware('throttle:10,1');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthenticationController::class, 'logout']);
            Route::get('/me', [AuthenticationController::class, 'me']);
        });
    });

    require __DIR__.'/api_content.php';
});
