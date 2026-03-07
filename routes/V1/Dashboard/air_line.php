<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\AirLineController;
use App\Http\Controllers\Api\V1\Dashboard\TicketReservationController;
use Illuminate\Support\Facades\Route;


Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'role:admin|supervisor',
])->apiResource('air-lines', AirLineController::class);


Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'role:admin|supervisor',
])->apiResource('ticket-reservation', TicketReservationController::class)->only(['index', 'show']);

Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'role:admin|supervisor',
])
    ->post('ticket-reservation/{id}', [TicketReservationController::class, 'changeStatus']);