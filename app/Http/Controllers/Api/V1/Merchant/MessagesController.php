<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Events\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use DB;
use Illuminate\Http\Request;
use Log;

class MessagesController extends Controller
{
    use ResponseTrait;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $request->validate(['content' => 'required']);
        try {
            $user = $request->user();
            $chat = $user->chat;
            $message = $user->chat()->firstOrFail()->messages()->create([
                'sender_id' => $user->id,
                'content' => $request->content,
                'chat_id' => $chat->id
            ]);
            event(new NewMessageSent($message));
            Log::info('Message sent and broadcasted: ', ['message' => $message]);
            DB::commit();
            return $this->showMessage('Message sent..!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->showError($e);
        }
    }
}
