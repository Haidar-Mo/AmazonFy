<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Merchant\ProductsController;
use App\Http\Controllers\Api\V1\Merchant\ShopsController;


Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'type.merchant'
])
    ->group(function () {
        Route::post('shops', [ShopsController::class, 'store']);
        Route::resource('shops', ShopsController::class)->only(['create', 'show', 'update', 'delete'])->middleware('shop_must_belong_to_user');
        Route::apiResource('shops/{shop}/products', ProductsController::class)->middleware('shop_must_belong_to_user');
    });

/**
 * TO DO LIST:
 *
 *
 *
 * put the expiration time in a config or .env file
 *
 *
 * QUESTIONS:
 *
 * the documentation notification when creating a new shop
 */
