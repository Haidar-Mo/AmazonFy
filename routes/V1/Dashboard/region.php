<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\RegionController;
use Illuminate\Support\Facades\Route;

Route::prefix('regions')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor',
    ])
    ->group(function () {

        Route::get('index', [RegionController::class, 'index'])
            ->middleware('hasAnyPermission:read-region|all');

        Route::get('show/{id}', [RegionController::class, 'show'])
            ->middleware('hasAnyPermission:read-region|all');

        Route::post('create', [RegionController::class, 'store'])
            ->middleware('hasAnyPermission:create-region|all');

        Route::post('locale-create', [RegionController::class, 'localeStore'])
            ->middleware('hasAnyPermission:create-region|all');

        Route::post('update/{id}', [RegionController::class, 'update'])
            ->middleware('hasAnyPermission:update-region|all');

        Route::delete('delete/{id}', [RegionController::class, 'destroy'])
            ->middleware('hasAnyPermission:delete-region|all');
    });
