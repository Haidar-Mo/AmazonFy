<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\MerchantController;
use App\Http\Controllers\Api\V1\Dashboard\ShopController;
use Illuminate\Support\Facades\Route;


route::prefix('merchants')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
    ])
    ->group(function () {

        Route::get('index', [MerchantController::class, 'index']);
        Route::get('show/{id}', [MerchantController::class, 'show']);
        Route::post('create', [MerchantController::class, 'store']);
        Route::post('update/{id}', [MerchantController::class, 'update']);
        Route::delete('delete/{id}', [MerchantController::class, 'destroy']);


        Route::prefix('status')->group(function () {

            Route::post('block/{id}', [MerchantController::class, 'blockMerchant']);
            Route::post('unblock/{id}', [MerchantController::class, 'unblockMerchant']);
        });



        Route::prefix('shops')->group(function () {

            Route::get('index', [ShopController::class, 'index']);
            Route::get('show/{id}', [ShopController::class, 'show']);
            Route::post('create', [ShopController::class, 'store']);
            Route::post('update/{id}', [ShopController::class, 'update']);
            Route::delete('delete/{id}', [ShopController::class, 'destroy']);
            Route::post('activate/{id}', [ShopController::class, 'activateShop']);
            Route::post('deactivate/{id}', [ShopController::class, 'deactivateShop']);
        });
    });