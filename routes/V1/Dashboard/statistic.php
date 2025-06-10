<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\StatisticsController;
use Illuminate\Support\Facades\Route;


Route::prefix('statistics')
    ->middleware([
        'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    ])
    ->group(function () {

        Route::get('show', [StatisticsController::class, 'show']);
    });