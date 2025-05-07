<?php

use Illuminate\Support\Facades\Route;


Route::prefix('v1/')->group(function () {

    Route::prefix('dashboard')->group(function () {
        include __DIR__ . "/V1/Dashboard/administration.php";
        include __DIR__ . "/V1/Dashboard/order.php";
    });


    Route::prefix('merchants')->group(function () {
        include __DIR__ . '/V1/Merchant/Auth.php';
        include __DIR__ . '/V1/Merchant/App.php';
    });
});
