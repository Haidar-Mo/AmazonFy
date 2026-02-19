<?php

use App\Enums\TokenAbility;
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
])->post('visa-requests/{id}', [VisaRequestController::class, 'updateStatus']);