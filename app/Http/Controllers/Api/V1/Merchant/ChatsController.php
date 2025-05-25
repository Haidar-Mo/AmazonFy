<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;

class ChatsController extends Controller
{
    use ResponseTrait;

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = auth()->user();
        $chat = $user->chat;
        $chat->message()->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $this->showResponse($chat->load('message'));
        // return $this->showResponse(new ChatResource($chat));
    }
}
