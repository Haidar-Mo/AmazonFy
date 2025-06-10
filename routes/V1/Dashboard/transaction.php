<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\TransactionController;
use Illuminate\Support\Facades\Route;


Route::prefix('transactions')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
    ])
    ->group(function () {

        Route::get('index', [TransactionController::class, 'index']);
        Route::post('handle/{id}', [TransactionController::class, 'handleTransaction']);
    });