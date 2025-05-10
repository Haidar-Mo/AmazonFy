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
    Route::get('logout', [LoginController::class, 'logout'])
        ->middleware([
            'auth:sanctum',
            'ability:' . TokenAbility::ACCESS_API->value,
            'type.merchant'
        ]);

    Route::post('email/verify/{id}', [RegisterController::class, 'verifyEmail']);

    Route::get('email/verify/{id}/resend',[RegisterController::class, 'resendVerificationCode']);

    Route::get('refresh-token', [LoginController::class, 'refreshToken'])
        ->middleware([
            'auth:sanctum',
            'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value
        ]);

});
