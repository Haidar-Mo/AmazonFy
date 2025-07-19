<?php

use App\Enums\TokenAbility;
use App\Events\ExampleEvent;
use App\Http\Controllers\Api\V1\Merchant\ChatsController;
use App\Http\Controllers\Api\V1\Merchant\MessagesController;
use App\Http\Controllers\Api\V1\Merchant\NotificationController;
use App\Http\Controllers\Api\V1\Merchant\OrdersController;
use App\Http\Controllers\Api\V1\Merchant\ProductsController;
use App\Http\Controllers\Api\V1\Merchant\ShopsController;
use App\Http\Controllers\Api\V1\Merchant\TransactionHistoriesController;
use App\Http\Controllers\Api\V1\Merchant\WalletAddressesController;
use App\Http\Controllers\Api\V1\Merchant\WalletsController;
use App\Http\Controllers\Api\V1\ProductTypesController;
use App\Http\Controllers\Api\V1\ShopTypesController;
use Illuminate\Support\Facades\Route;


Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'type.merchant'
])
    ->group(function () {

        Route::get('products/index', [ProductsController::class, 'index']);
        Route::get('products/index/p', [ProductsController::class, 'indexPaginate']); // paginate: replace this with the upper one and do not forget to delete the function 
    
        Route::resource('shops', ShopsController::class)->only(['store'])->middleware('user_must_not_be_blocked');

        Route::get('chat', [ChatsController::class, 'show']);

        Route::apiResource('messages', MessagesController::class)->only(['store']);

        Route::get('wallet', [WalletsController::class, 'show']);
        Route::get('wallet/transactionHistory', [TransactionHistoriesController::class, 'index']);
        Route::get('wallet/admin/addresses', [WalletsController::class, 'indexAllAdminAddresses']);
        Route::post('wallet/charge', [WalletsController::class, 'chargeBalance']);

        Route::middleware('user_must_not_be_blocked')->group(function () {
            Route::post('wallet/withdraw', [WalletsController::class, 'withdrawBalance']);

            Route::put('wallet/updatePassword', [WalletsController::class, 'update']);
            Route::post('wallet/walletAddresses', [WalletAddressesController::class, 'store']);

            Route::middleware('address_must_belong_to_wallet')->group(function () {
                Route::apiResource('wallet/walletAddresses', WalletAddressesController::class)->only(['update', 'destroy']);
            });

        });

        Route::middleware(['merchant_must_be_documented', 'merchant_must_be_active'])->group(function () {
            Route::get('shop', [ShopsController::class, 'show']);
            Route::get('shop/statistics', [ShopsController::class, 'getStatistics']);
            Route::get('shop/shopOrders', [OrdersController::class, 'index']);
        });

        Route::middleware(['user_must_not_be_blocked', 'merchant_must_be_documented', 'merchant_must_be_active'])->group(function () {
            // Route::apiResource('shops', ShopsController::class)->only(['show', 'update', 'destroy'])->middleware('shop_must_belong_to_user');
            Route::put('shop', [ShopsController::class, 'update']);
            Route::delete('shop', [ShopsController::class, 'destroy']);


            Route::apiResource('shops/products', ProductsController::class)->only(['store', 'destroy']);

            Route::put('shop/shopOrders/{shopOrder}', [OrdersController::class, 'update']);
            // Route::apiResource('shop/shopOrders', OrdersController::class)->only(['index', 'update']);
        });

        Route::prefix('notifications')->middleware(['merchant_must_be_documented', 'merchant_must_be_active'])
            ->group(
                function () {
                    Route::get('index', [NotificationController::class, 'index']);
                }
            );

        //? isolated API To check id the user has documented shop or not
        Route::get('is-documented', function () {
            return (auth()->user()->shop?->status == 'active') ? (object) ['is_documented' => true] : (object) ['is_documented' => false];
        });

    });

//: This is guest user index products
Route::get('products', [ProductsController::class, 'getProductsForGuest']);
Route::get('products/p', [ProductsController::class, 'getProductsForGuestPaginate']); // paginate: replace this with the upper one

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
