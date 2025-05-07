<?php


use App\Http\Controllers\Api\V1\Merchant\Auth\{
    RegisterController,
    LoginController
};

use App\Enums\TokenAbility;


// Auth Routes

Route::prefix('auth/')->group(function () {
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login', [LoginController::class, 'login']);
    Route::get('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

    Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verifyEmail'])
        ->middleware(['signed'])
        ->name('verification.verify');

    Route::get('refresh-token', [LoginController::class, 'refreshToken'])
        ->middleware([
            'auth:sanctum',
            'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value
        ]);

});
