<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\MerchantController;
use App\Http\Controllers\Api\V1\Dashboard\ShopController;
use Illuminate\Support\Facades\Route;

route::prefix('merchants')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor',
    ])
    ->group(function () {

        Route::get('index', [MerchantController::class, 'index'])
            ->middleware('hasAnyPermission:read-merchant|all');

        Route::get('show/{id}', [MerchantController::class, 'show'])
            ->middleware('hasAnyPermission:read-merchant|all');

        Route::post('create', [MerchantController::class, 'store'])
            ->middleware('hasAnyPermission:create-merchant|all');

        Route::post('update/{id}', [MerchantController::class, 'update'])
            ->middleware('hasAnyPermission:update-merchant|all');

        Route::delete('delete/{id}', [MerchantController::class, 'destroy'])
            ->middleware('hasAnyPermission:delete-merchant|all');

        Route::prefix('status')->group(function () {
            Route::post('block/{id}', [MerchantController::class, 'blockMerchant'])
                ->middleware('hasAnyPermission:update-merchant|all');

            Route::post('unblock/{id}', [MerchantController::class, 'unblockMerchant'])
                ->middleware('hasAnyPermission:update-merchant|all');
        });

        Route::prefix('shops')->group(function () {

            Route::get('index', [ShopController::class, 'index'])
                ->middleware('hasAnyPermission:read-shop|all');

            Route::get('show/{id}', [ShopController::class, 'show'])
                ->middleware('hasAnyPermission:read-shop|all');

            Route::post('create', [ShopController::class, 'store'])
                ->middleware('hasAnyPermission:create-shop|all');

            Route::post('update/{id}', [ShopController::class, 'update'])
                ->middleware('hasAnyPermission:update-shop|all');

            Route::delete('delete/{id}', [ShopController::class, 'destroy'])
                ->middleware('hasAnyPermission:delete-shop|all');

            Route::post('activate/{id}', [ShopController::class, 'activateShop'])
                ->middleware('hasAnyPermission:update-shop|all');

            Route::post('deactivate/{id}', [ShopController::class, 'deactivateShop'])
                ->middleware('hasAnyPermission:update-shop|all');
        });
    });
