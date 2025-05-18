<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\Merchant\ProductsController;
use App\Http\Controllers\Api\V1\Merchant\ShopsController;
use App\Http\Controllers\Api\V1\Merchant\WalletAddressesController;
use App\Http\Controllers\Api\V1\Merchant\WalletsController;
use App\Http\Controllers\Api\V1\MessageController;


Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'type.merchant'
])
    ->group(function () {
        Route::post('shops', [ShopsController::class, 'store']);

        # all these route should be under the merchant role
        Route::resource('shops', ShopsController::class)->only(['create', 'show', 'update', 'destroy'])->middleware('shop_must_belong_to_user');
        Route::get('products', [ProductsController::class, 'index']);
        Route::apiResource('shops/{shop}/products', ProductsController::class)->only(['store', 'destroy'])->middleware('shop_must_belong_to_user');


        Route::middleware('wallet_must_belong_to_user')->group(function () {
            Route::apiResource('wallets', WalletsController::class)->only('show');
            Route::middleware('address_must_belong_to_wallet')->group(function () {
                Route::post('wallets/{wallet}/charge', [WalletsController::class, 'chargeBalance']);
                Route::post('wallets/{wallet}/withdraw', [WalletsController::class, 'withdrawBalance']);
                Route::apiResource('wallets/{wallet}/walletAddresses', WalletAddressesController::class)->only(['store', 'update', 'destroy']);
            });
        });
        Route::prefix('chats')->group(function () {

            // Route::get('show/{id}', [ChatController::class, 'show']);
        });
        // Route::apiResource('messages', MessageController::class)->only(['store']);
    });

/**
 * TO DO LIST:
 *
 * Charge & withdraw balance notifications to admin
 *
 * the documentation notification when creating a new shop
 *
 * put the expiration time for verification code in a config or .env file
 *
 * add the merchant role middleware for routes in this file
 *
 * dot't forget to add only() for the used routes and ignore the others created by apiResource
 *
 * QUESTIONS:
 *
 *
 */
