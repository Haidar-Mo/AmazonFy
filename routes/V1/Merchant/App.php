<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Merchant\ChatsController;
use App\Http\Controllers\Api\V1\Merchant\MessagesController;
use App\Http\Controllers\Api\V1\Merchant\OrdersController;
use App\Http\Controllers\Api\V1\Merchant\ProductsController;
use App\Http\Controllers\Api\V1\Merchant\ShopsController;
use App\Http\Controllers\Api\V1\Merchant\WalletAddressesController;
use App\Http\Controllers\Api\V1\Merchant\WalletsController;


Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'type.merchant'
])
    ->group(function () {
        // Route::post('shops', [ShopsController::class, 'store']);
        Route::resource('shops', ShopsController::class)->only(['create', 'store']);

        Route::middleware(['merchant_must_be_documented', 'merchant_must_be_active'])->group(function () {
            Route::apiResource('shops', ShopsController::class)->only(['show', 'update', 'destroy'])->middleware('shop_must_belong_to_user');
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

            Route::get('chats/show/{id}', [ChatsController::class, 'show']);

            Route::apiResource('messages', MessagesController::class)->only(['store']);

            Route::apiResource('shops/{shop}/shopOrders', OrdersController::class)->only(['index', 'update']);
        });

    });

//! don't forget to set the proper admin id in register controller ^2 and wallets controller ^2 (waiting for haidar)
/**
 * TO DO LIST:
 *
  //!!!!!!!ALL ORDERS NOTIFICATIONS BABYYYYY
 *
 * Charge & withdraw balance notifications to admin //! testing
 *
 * the documentation notification when creating a new shop //! testing
 *
 * put the expiration time for verification code in a config or .env file //! Code service
 *
 * add the merchant role middleware for routes in this file
 *
 * dot't forget to add only() for the used routes and ignore the others created by apiResource
 *
 * QUESTIONS:
 *
 *
 */
