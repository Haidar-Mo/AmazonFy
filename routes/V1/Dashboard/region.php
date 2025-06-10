<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\RegionController;
use Illuminate\Support\Facades\Route;


Route::prefix('regions')
    ->middleware([
        'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    ])
    ->group(function () {

        Route::get('index', [RegionController::class, 'index']);
        Route::get('show/{id}', [RegionController::class, 'show']);
        Route::post('create', [RegionController::class, 'store']);
        Route::post('update/{id}', [RegionController::class, 'update']);
        Route::delete('delete/{id}', [RegionController::class, 'destroy']);
    });