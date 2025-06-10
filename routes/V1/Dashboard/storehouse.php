<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\StorehouseController;
use Illuminate\Support\Facades\Route;

Route::prefix('storehouse')
    ->middleware([
        'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    ])
    ->group(function () {

        Route::get('index', [StorehouseController::class, 'index']);
        Route::post('create', [StorehouseController::class, 'store']);
        Route::delete('delete/{id}', [StorehouseController::class, 'destroy']);
    });