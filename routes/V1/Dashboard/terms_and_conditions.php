<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\TermsAndConditionsController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;


Route::prefix('terms-and-conditions')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
    ])->group(function () {

        Route::get('show', [TermsAndConditionsController::class, 'show']);
        Route::post('update', [TermsAndConditionsController::class, 'update']);
    });