<?php

use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    Route::prefix('dashboard')->group(function () {
        include __DIR__ . "/V1/Dashboard/administration.php";
    });


    Route::prefix('mobile')->group(function () {

    });
});