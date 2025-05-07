<?php

use App\Http\Controllers\Api\V1\dashboard\TermsAndConditionsController;
use Illuminate\Support\Facades\Route;


Route::prefix('terms-and-conditions')->group(function () {

    Route::get('show', [TermsAndConditionsController::class, 'show']);
    Route::post('update', [TermsAndConditionsController::class, 'update']);
});