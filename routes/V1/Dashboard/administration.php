<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\AdministrationController;
use Illuminate\Support\Facades\Route;

Route::prefix('administrations')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor'
    ])
    ->group(function () {
        Route::get('index', [AdministrationController::class, 'index'])
            ->middleware('hasAnyPermission:read-user|all');

        Route::get('show/{id}', [AdministrationController::class, 'show'])
            ->middleware('hasAnyPermission:read-user|all');

        Route::post('store', [AdministrationController::class, 'store'])
            ->middleware('hasAnyPermission:create-user|all');

        Route::post('update/{id}', [AdministrationController::class, 'update'])
            ->middleware('hasAnyPermission:update-user|all');

        Route::delete('delete/{id}', [AdministrationController::class, 'destroy'])
            ->middleware('hasAnyPermission:delete-user|all');

        Route::get('permissions/index', [AdministrationController::class, 'indexPermissions'])
            ->middleware('hasAnyPermission:read-user|all');
    });
