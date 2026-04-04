<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\VisaArrangementController;
use App\Http\Controllers\Api\V1\Dashboard\VisaController;
use App\Http\Controllers\Api\V1\Dashboard\VisaRequestController;
use Illuminate\Support\Facades\Route;


Route::apiResource('visas', VisaController::class)
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor',
    ]);
Route::apiResource('visa-requests', VisaRequestController::class)->except(['update', 'create', 'edit', 'store'])
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor',
    ]);

Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'role:admin|supervisor',
])->get('index-visa-requests', [VisaRequestController::class, 'index']);

Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'role:admin|supervisor',
])->post('visa-requests/{id}', [VisaRequestController::class, 'updateStatus']);


Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'role:admin|supervisor',
])->prefix('visa-arrangements')
    ->group(function () {

        Route::get('index', [VisaArrangementController::class, 'index']);
        Route::get('show/{id}', [VisaArrangementController::class, 'show']);
        Route::post('accept/{id}', [VisaArrangementController::class, 'accept']);
        Route::post('reject/{id}', [VisaArrangementController::class, 'reject']);
    });