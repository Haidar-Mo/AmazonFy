<?php

use App\Http\Controllers\Api\V1\Dashboard\StatisticsController;
use Illuminate\Support\Facades\Route;


Route::prefix('statistics')
    ->middleware([])
    ->group(function () {

        Route::get('show', [StatisticsController::class, 'show']);
    });