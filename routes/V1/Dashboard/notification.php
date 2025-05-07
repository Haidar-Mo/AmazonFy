<?php

use App\Http\Controllers\Api\V1\dashboard\NotificationController;
use Illuminate\Support\Facades\Route;


Route::prefix('notifications')->group(function () {

    Route::get('index', [NotificationController::class, 'index']);
    Route::get('show/{id}', [NotificationController::class, 'show']);
    Route::post('create/{id}', [NotificationController::class, 'store']);
    Route::delete('delete/{id}', [NotificationController::class, 'destroy']);

});