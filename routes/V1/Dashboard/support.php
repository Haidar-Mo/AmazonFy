<?php


use App\Http\Controllers\Api\V1\MessageController;
use Illuminate\Support\Facades\Route;


Route::prefix('support')
    ->middleware([])
    ->group(function () {

        Route::prefix('chats')->group(function () {

            // Route::get('index', [MessageController::class, 'index']);
            // Route::get('show/{chat}', [MessageController::class, 'show']);
        });

        Route::prefix('messages')->group(function () {

            // Route::post('create', [MessageController::class, 'store']);
        });
    });