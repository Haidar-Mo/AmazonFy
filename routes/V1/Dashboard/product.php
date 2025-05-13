<?php

use App\Http\Controllers\Api\V1\Dashboard\ProductController;
use App\Http\Controllers\Api\V1\Dashboard\ProductTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')
    ->middleware([])
    ->group(function () {

        Route::get('index', [ProductController::class, 'index']);
        Route::get('show/{id}', [ProductController::class, 'show']);
        Route::post('create', [ProductController::class, 'store']);
        Route::post('update/{id}', [ProductController::class, 'update']);
        Route::delete('delete/{id}', [ProductController::class, 'destroy']);

        Route::prefix('types')->group(function () {
            Route::get('index', [ProductTypeController::class, 'index']);
            Route::get('show/{id}', [ProductTypeController::class, 'show']);
            Route::post('create', [ProductTypeController::class, 'store']);
            Route::post('update/{id}', [ProductTypeController::class, 'update']);
            Route::delete('delete/{id}', [ProductTypeController::class, 'destroy']);
        });
    });