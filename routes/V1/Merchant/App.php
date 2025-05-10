<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Merchant\ShopsController;


Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'type.merchant'
])
    ->group(function () {

        Route::apiResource('shops',ShopsController::class);

    });

/**
 * TO DO LIST:
 *
 * put the expiration time in a config or .env file
 */
