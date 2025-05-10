<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Merchant\ShopDocumentationsController;


Route::middleware([
    'auth:sanctum',
    'ability:' . TokenAbility::ACCESS_API->value,
    'type.merchant'
])
    ->group(function () {

        Route::post('send-documentation-request', [ShopDocumentationsController::class, 'sendShopDocumentationRequest']);

    });

    /**
     * TO DO LIST:
     *
     *
     *
     * put the expiration time in a config or .env file
     *
     *
     * QUESTIONS:
     *
     * the type column in shop table?? is it same as tasneef?
     */
