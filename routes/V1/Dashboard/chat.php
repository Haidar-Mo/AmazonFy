<?php

use App\Enums\TokenAbility;
use App\Http\Controllers\Api\V1\Dashboard\ChatsAndMessagesController;
use Illuminate\Support\Facades\Route;

Route::prefix('chats')
    ->middleware([
        'auth:sanctum',
        'ability:' . TokenAbility::ACCESS_API->value,
        'role:admin|supervisor',
    ])
    ->group(function () {
        Route::get('index', [ChatsAndMessagesController::class, 'indexChats'])
            ->middleware('hasAnyPermission:read-chat|all');

        Route::get('show/{id}', [ChatsAndMessagesController::class, 'showChat'])
            ->middleware('hasAnyPermission:read-chat|all');

        Route::post('store', [ChatsAndMessagesController::class, 'storeChat'])
            ->middleware('hasAnyPermission:create-chat|all');

        Route::delete('delete/{id}', [ChatsAndMessagesController::class, 'destroy'])
            ->middleware('hasAnyPermission:delete-chat|all');

        Route::post('message-send', [ChatsAndMessagesController::class, 'storeMessage'])
            ->middleware('hasAnyPermission:update-chat|all');
    });