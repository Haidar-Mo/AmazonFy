<?php

use App\Http\Controllers\Api\V1\Dashboard\TransactionController;
use Illuminate\Support\Facades\Route;


Route::prefix('transactions')
    ->middleware([])
    ->group(function () {

        Route::get('index', [TransactionController::class, 'index']);
        Route::post('handle/{id}', [TransactionController::class, 'handleTransaction']);
    });