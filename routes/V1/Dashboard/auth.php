<?php

use App\Http\Controllers\Api\V1\Dashboard\Auth\LoginController;
use App\Http\Controllers\Api\V1\Dashboard\Auth\ProfileController;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')
    ->middleware([])
    ->group(function () {

        Route::post('login', [LoginController::class, 'login']);
        Route::post('logout', [LoginController::class, 'logout']);

        Route::post('profile/update', [ProfileController::class, 'update']);
    });