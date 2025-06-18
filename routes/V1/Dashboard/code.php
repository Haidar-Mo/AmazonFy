<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\RepresentativeCodeController;
use Illuminate\Support\Facades\Route;

Route::prefix('codes')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor',
    ])
    ->group(function () {
        Route::get('index', [RepresentativeCodeController::class, 'index'])
            ->middleware('hasAnyPermission:all');


        Route::post('create', [RepresentativeCodeController::class, 'store'])
            ->middleware('hasAnyPermission:all');

        Route::delete('delete/{id}', [RepresentativeCodeController::class, 'destroy'])
            ->middleware('hasAnyPermission:all');
    });