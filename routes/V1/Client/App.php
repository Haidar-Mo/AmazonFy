<?php

use App\Http\Controllers\Api\V1\Client\OrdersController;
use App\Http\Controllers\Api\V1\Client\ShopsController;

Route::middleware('guest')->group(function () {

    Route::apiResource('shops', ShopsController::class)->only('index', 'show');

    Route::apiResource('orders',OrdersController::class)->only('store');
});
