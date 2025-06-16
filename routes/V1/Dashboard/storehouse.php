<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\StorehouseController;
use Illuminate\Support\Facades\Route;

Route::prefix('storehouse')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor',
    ])
    ->group(function () {

        Route::get('index', [StorehouseController::class, 'index'])
            ->middleware('hasAnyPermission:read-storehouse|all');

        Route::post('create', [StorehouseController::class, 'store'])
            ->middleware('hasAnyPermission:create-storehouse|all');

        Route::delete('delete/{id}', [StorehouseController::class, 'destroy'])
            ->middleware('hasAnyPermission:delete-storehouse|all');
    });
