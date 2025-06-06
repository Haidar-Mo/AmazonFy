<?php

use App\Http\Controllers\Api\V1\Dashboard\OrderController;
use Illuminate\Support\Facades\Route;


Route::prefix('orders')
    ->middleware([])
    ->group(function () {

        Route::get('index', [OrderController::class, 'index']);
        Route::get('show/{id}', [OrderController::class, 'show']);
        Route::post('update/{id}', [OrderController::class, 'update']);
        Route::post('cancel/{id}', [OrderController::class, 'cancelOrder']);
        Route::delete('delete/{id}', [OrderController::class, 'destroy']);

    });