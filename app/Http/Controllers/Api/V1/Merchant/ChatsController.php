<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Traits\ResponseTrait;
use Auth;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    use ResponseTrait;

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = Auth::user();
        $chat = $user->chat;
        $chat->messages()->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $this->showResponse($chat->load('messages'));
        // return $this->showResponse(new ChatResource($chat));
    }
}
