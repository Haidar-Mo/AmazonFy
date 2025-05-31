<?php

use App\Http\Controllers\Api\V1\Dashboard\ShopTypesController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1/')->group(function () {

    Route::prefix('dashboard')->group(function () {
        include __DIR__ . "/V1/Dashboard/administration.php";
        include __DIR__ . "/V1/Dashboard/merchant.php";
        include __DIR__ . "/V1/Dashboard/region.php";
        include __DIR__ . "/V1/Dashboard/product.php";
        include __DIR__ . "/V1/Dashboard/order.php";
        include __DIR__ . "/V1/Dashboard/notification.php";
        include __DIR__ . "/V1/Dashboard/terms_and_conditions.php";
        include __DIR__ . "/V1/Dashboard/storehouse.php";
        include __DIR__ . "/V1/Dashboard/chat.php";
        include __DIR__ . "/V1/Dashboard/transaction.php";
        include __DIR__ . "/V1/Dashboard/address.php";
        include __DIR__ . "/V1/Dashboard/statistic.php";

        Route::apiResource('shopTypes', ShopTypesController::class);
    });


    Route::prefix('merchants/')->group(function () {
        include __DIR__ . '/V1/Merchant/Auth.php';
        include __DIR__ . '/V1/Merchant/App.php';
    });

    Route::prefix('clients')->group(function () {
        include __DIR__ . '/V1/Client/App.php';
    });
});
