<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('support.1', function ($user) {
//     return true;
// });

Broadcast::channel('support.{chatId}', function (User $user, int $chatId) {
    $chat = Chat::findOrFail($chatId);
    return $user->id === $chat->user_id;
});
