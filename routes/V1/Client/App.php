<?php

use App\Http\Controllers\Api\V1\Client\OrdersController;
use App\Http\Controllers\Api\V1\Client\ShopsController;
use App\Http\Controllers\Api\V1\ProductTypesController;
use App\Http\Controllers\Api\V1\ShopTypesController;

Route::middleware('guest')->group(function () {

    Route::apiResource('shops', ShopsController::class)->only('index', 'show');

    Route::apiResource('orders', OrdersController::class)->only('store');

    Route::apiResource('shopTypes', ShopTypesController::class)->only('index');
    Route::apiResource('productTypes', ProductTypesController::class)->only('index');
});
