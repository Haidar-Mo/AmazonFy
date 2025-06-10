<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\AdministrationController;
use Illuminate\Support\Facades\Route;


Route::prefix('administrations')
    ->middleware([
        'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    ])
    ->group(function () {

        Route::get('index', [AdministrationController::class, 'index']);
        Route::get('show/{id}', [AdministrationController::class, 'show']);
        Route::post('store', [AdministrationController::class, 'store']);
        Route::post('update/{id}', [AdministrationController::class, 'update']);
        Route::delete('delete/{id}', [AdministrationController::class, 'destroy']);


        Route::get('permissions/index', [AdministrationController::class, 'indexPermissions']);
    });