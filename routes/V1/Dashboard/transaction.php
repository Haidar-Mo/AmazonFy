<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\TransactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('transactions')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor',
    ])
    ->group(function () {

        Route::get('index', [TransactionController::class, 'index'])
            ->middleware('hasAnyPermission:read-transaction|all');

        Route::post('handle/{id}', [TransactionController::class, 'handleTransaction'])
            ->middleware('hasAnyPermission:update-transaction|all');

        Route::post('create/{id}', [TransactionController::class, 'createTransaction'])
            ->middleware('hasAnyPermission:create-transaction|all');
    });
