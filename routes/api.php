<?php

use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::prefix('dashboard')->group(function () {
        include __DIR__ . "/V1/Dashboard/administration.php";
        include __DIR__ . "/V1/Dashboard/product.php";
        include __DIR__ . "/V1/Dashboard/order.php";
        include __DIR__ . "/V1/Dashboard/notification.php";
        include __DIR__ . "/V1/Dashboard/terms_and_conditions.php";
        include __DIR__ . "/V1/Dashboard/storehouse.php";
    });


    Route::prefix('mobile')->group(function () {

    });
});