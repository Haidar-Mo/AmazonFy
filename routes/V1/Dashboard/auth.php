<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\Auth\LoginController;
use App\Http\Controllers\Api\V1\Dashboard\Auth\ProfileController;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')
    ->group(function () {

        Route::post('login', [LoginController::class, 'login']);
        Route::middleware([
            'auth:sanctum',
            'ability:' . TokenAbility::ACCESS_API->value,
        ])->group(function () {

            Route::post('logout', [LoginController::class, 'logout']);

            Route::get('profile/show', [ProfileController::class, 'show']);
            Route::post('profile/update', [ProfileController::class, 'update']);
        });
    });