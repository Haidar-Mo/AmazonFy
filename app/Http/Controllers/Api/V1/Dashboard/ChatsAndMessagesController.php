<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Events\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CreateChatRequest;
use App\Http\Requests\Api\V1\CreateMessageRequest;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatsAndMessagesController extends Controller
{
    use ResponseTrait;


    public function indexChats(Request $request)
    {
        $chats = Chat::all()->each(function ($chat) {
            $chat->append(['merchant_name']);
        });
        return $this->showResponse($chats, 'تم جلب المحادثات');
    }

    public function showChat(string $id)
    {
        $chat = Chat::with(['user.shop', 'message'])
            ->findOrFail($id);
        $chat->message()->where('sender_id', '!=', request()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // return $this->showResponse($chat, 'done successfully...!');
        return $this->showResponse(new ChatResource($chat), 'done successfully...!');
    }


    public function storeChat(CreateChatRequest $request)
    {
        $admin = $request->user();
        DB::beginTransaction();
        try {
            $chat = Chat::FirstOrCreate([
                'user_id' => $request->user_id,
            ]);
            DB::commit();
            return $this->showResponse($chat, 'chat created successfully....!');
        } catch (\Exception $e) {
            return $this->showError($e, 'something goes wrong....!');
        }
    }


    public function storeMessage(CreateMessageRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $request->user();
            $message = Chat::where('id', $request->chat_id)->firstOrFail()->message()->create([
                'sender_id' => $user->id,
                'content' => $request->content,
                'chat_id' => $request->chat_id
            ]);
            event(new NewMessageSent($message));
            Log::info('Message sent and broadcasted: ', ['message' => $message]);
            DB::commit();
            return $this->showResponse($message, 'Message sent..!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong...!');
        }
    }
}
