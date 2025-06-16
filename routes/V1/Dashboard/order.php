<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor',
    ])
    ->group(function () {

        Route::get('index', [OrderController::class, 'index'])
            ->middleware('hasAnyPermission:read-order|all');

        Route::get('show/{id}', [OrderController::class, 'show'])
            ->middleware('hasAnyPermission:read-order|all');

        Route::post('create', [OrderController::class, 'store'])
            ->middleware('hasAnyPermission:create-order|all');

        Route::post('update/{id}', [OrderController::class, 'update'])
            ->middleware('hasAnyPermission:update-order|all');

        Route::post('cancel/{id}', [OrderController::class, 'cancel'])
            ->middleware('hasAnyPermission:update-order|all');
    });
