<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\TermsAndConditionsController;
use Illuminate\Support\Facades\Route;

Route::prefix('terms-and-conditions')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor',
    ])->group(function () {

        Route::get('show', [TermsAndConditionsController::class, 'show'])
            ->middleware('hasAnyPermission:read-terms|all');

        Route::post('update', [TermsAndConditionsController::class, 'update'])
            ->middleware('hasAnyPermission:update-terms|all');
    });
