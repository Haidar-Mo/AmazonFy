<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\AddressController;
use Illuminate\Support\Facades\Route;

Route::prefix('addresses')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
    ])
    ->group(function () {

        Route::get('index', [AddressController::class, 'index']);
        Route::post('create', [AddressController::class, 'store']);
        Route::post('update/{id}', [AddressController::class, 'update']);
        Route::delete('delete/{id}', [AddressController::class, 'destroy']);
    });