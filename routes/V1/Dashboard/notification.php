<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\NotificationController;
use Illuminate\Support\Facades\Route;


Route::prefix('notifications')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor'
    ])
    ->group(function () {

        Route::get('index', [NotificationController::class, 'indexSended']);
        Route::get('index-received', [NotificationController::class, 'indexReceived']);
        Route::get('show/{id}', [NotificationController::class, 'show']);
        Route::post('create/{id}', [NotificationController::class, 'store']);
        Route::delete('delete/{id}', [NotificationController::class, 'destroy']);

        Route::get('count', [NotificationController::class, 'countNotification']);
    });