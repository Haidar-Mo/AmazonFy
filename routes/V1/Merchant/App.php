<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Merchant\ShopsController;


Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'type.merchant'
])
    ->group(function () {

        Route::resource('shops',ShopsController::class);

    });

/**
 * TO DO LIST:
 *
 * proceed with prodacts CRUD
 *
 * put the expiration time in a config or .env file
 *
 *
 * QUESTIONS:
 *
 * the documentation notification when creating a new shop
 */
