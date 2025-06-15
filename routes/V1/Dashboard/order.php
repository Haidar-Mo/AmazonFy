<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\OrderController;
use Illuminate\Support\Facades\Route;


Route::prefix('orders')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
    ])
    ->group(function () {

        Route::get('index', [OrderController::class, 'index']);
        Route::get('show/{id}', [OrderController::class, 'show']);
        Route::post('create', [OrderController::class, 'store']);
        Route::post('update/{id}', [OrderController::class, 'update']);
        Route::post('cancel/{id}', [OrderController::class, 'cancel']);

    });