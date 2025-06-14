<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\ChatsAndMessagesController;
use Illuminate\Support\Facades\Route;

Route::prefix('chats')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
    ])
    ->group(function () {

        Route::get('index', [ChatsAndMessagesController::class, 'indexChats']);
        Route::get('show/{id}', [ChatsAndMessagesController::class, 'showChat']);
        Route::post('store', [ChatsAndMessagesController::class, 'storeChat']);
        Route::delete('delete/{id}', [ChatsAndMessagesController::class, 'destroy']);
        Route::post('message-send', [ChatsAndMessagesController::class, 'storeMessage']);
    });