<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\ProductController;
use App\Http\Controllers\Api\V1\Dashboard\ProductTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('products')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor',
    ])
    ->group(function () {

        Route::get('index', [ProductController::class, 'index'])
            ->middleware('hasAnyPermission:read-product|all');
        Route::get('index/p', [ProductController::class, 'indexPaginate'])
            ->middleware('hasAnyPermission:read-product|all');

        Route::get('show/{id}', [ProductController::class, 'show'])
            ->middleware('hasAnyPermission:read-product|all');

        Route::post('create', [ProductController::class, 'store'])
            ->middleware('hasAnyPermission:create-product|all');

        Route::post('locale-create', [ProductController::class, 'localeStore'])
            ->middleware('hasAnyPermission:create-product|all');

        Route::post('update/{id}', [ProductController::class, 'update'])
            ->middleware('hasAnyPermission:update-product|all');

        Route::delete('delete/{id}', [ProductController::class, 'destroy'])
            ->middleware('hasAnyPermission:delete-product|all');

        Route::prefix('types')->group(function () {

            Route::get('index', [ProductTypeController::class, 'index'])
                ->middleware('hasAnyPermission:read-product-type|all');

            Route::get('show/{id}', [ProductTypeController::class, 'show'])
                ->middleware('hasAnyPermission:read-product-type|all');

            Route::post('create', [ProductTypeController::class, 'store'])
                ->middleware('hasAnyPermission:create-product-type|all');

            Route::post('update/{id}', [ProductTypeController::class, 'update'])
                ->middleware('hasAnyPermission:update-product-type|all');

            Route::delete('delete/{id}', [ProductTypeController::class, 'destroy'])
                ->middleware('hasAnyPermission:delete-product-type|all');
        });
    });
