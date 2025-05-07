<?php

use App\Http\Controllers\Api\V1\Dashboard\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')->group(function () {

    Route::get('index', [ProductController::class, 'index']);
    Route::get('show/{id}', [ProductController::class, 'show']);
    Route::post('create', [ProductController::class, 'store']);
    Route::post('update/{id}', [ProductController::class, 'update']);
    Route::delete('delete/{id}', [ProductController::class, 'destroy']);
});