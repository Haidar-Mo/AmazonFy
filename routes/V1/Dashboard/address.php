<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\AddressController;
use Illuminate\Support\Facades\Route;

Route::prefix('addresses')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor'
    ])
    ->group(function () {
        Route::get('index', [AddressController::class, 'index'])
            ->middleware('hasAnyPermission:read-address|all');

        Route::post('create', [AddressController::class, 'store'])
            ->middleware('hasAnyPermission:create-address|all');

        Route::post('update/{id}', [AddressController::class, 'update'])
            ->middleware('hasAnyPermission:update-address|all');

        Route::delete('delete/{id}', [AddressController::class, 'destroy'])
            ->middleware('hasAnyPermission:delete-address|all');
    });
