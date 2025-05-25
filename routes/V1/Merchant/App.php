<?php

use App\Enums\TokenAbility;
use App\Events\ExampleEvent;
use App\Http\Controllers\Api\V1\Merchant\ChatsController;
use App\Http\Controllers\Api\V1\Merchant\MessagesController;
use App\Http\Controllers\Api\V1\Merchant\OrdersController;
use App\Http\Controllers\Api\V1\Merchant\ProductsController;
use App\Http\Controllers\Api\V1\Merchant\ShopsController;
use App\Http\Controllers\Api\V1\Merchant\TransactionHistoriesController;
use App\Http\Controllers\Api\V1\Merchant\WalletAddressesController;
use App\Http\Controllers\Api\V1\Merchant\WalletsController;
use App\Http\Controllers\Api\V1\ProductTypesController;
use App\Http\Controllers\Api\V1\ShopTypesController;


Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'type.merchant'
])
    ->group(function () {
        Route::resource('shops', ShopsController::class)->only(['store']);

        Route::middleware(['merchant_must_be_documented', 'merchant_must_be_active'])->group(function () {
            // Route::apiResource('shops', ShopsController::class)->only(['show', 'update', 'destroy'])->middleware('shop_must_belong_to_user');
            Route::get('shop', [ShopsController::class, 'show']);
            Route::get('shop/statistics',[ShopsController::class,'getStatistics']);
            Route::put('shop', [ShopsController::class, 'update']);
            Route::delete('shop', [ShopsController::class, 'destroy']);


            Route::apiResource('shops/products', ProductsController::class)->only(['store', 'destroy']);

            Route::get('wallet', [WalletsController::class, 'show']);
            Route::post('wallet/walletAddresses', [WalletAddressesController::class, 'store']);
            Route::get('wallet/transactionHistory', [TransactionHistoriesController::class, 'index']);

            Route::middleware('address_must_belong_to_wallet')->group(function () {
                Route::post('wallet/charge', [WalletsController::class, 'chargeBalance']);
                Route::post('wallet/withdraw', [WalletsController::class, 'withdrawBalance']);
                Route::apiResource('wallet/walletAddresses', WalletAddressesController::class)->only(['update', 'destroy']);
            });

            Route::get('chat', [ChatsController::class, 'show']);

            Route::apiResource('messages', MessagesController::class)->only(['store']);

            Route::apiResource('shop/shopOrders', OrdersController::class)->only(['index', 'update']);
        });

    });

Route::get('products', [ProductsController::class, 'index']);

Route::apiResource('shopTypes', ShopTypesController::class)->only('index');
Route::apiResource('productTypes', ProductTypesController::class)->only('index');

Route::get('/trigger-event', function () {
    ExampleEvent::dispatch('Hello from Laravel!');

    return response()->json(['status' => 'Event triggered']);
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
