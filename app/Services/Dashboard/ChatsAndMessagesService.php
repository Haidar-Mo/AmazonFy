<?php

namespace App\Services\Dashboard;
use App\Events\NewMessageSent;
use App\Http\Requests\Api\V1\CreateChatRequest;
use App\Http\Requests\Api\V1\CreateMessageRequest;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ChatService.
 */
class ChatsAndMessagesService
{

    public function indexChats(Request $request)
    {
        return Chat::all()->each(function ($chat) {
            $chat->append(['merchant_name']);
        });
    }

    public function showChat(string $id)
    {
        $chat = Chat::with(['user.shop', 'message'])
            ->findOrFail($id);

        $chat->message()
            ->where('sender_id', '!=', request()->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return new ChatResource($chat);
    }

    public function storeChat(FormRequest $request)
    {
        return Chat::firstOrCreate([
            'user_id' => $request->user_id,
        ]);
    }

    public function destroyChat(string $id)
    {
        $chat = Chat::findOrFail($id);
        DB::transaction(function () use ($chat) {
            $chat->delete();
        });
    }

    public function storeMessage(CreateMessageRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $request->user();
            $message = Chat::where('id', $request->chat_id)
                ->firstOrFail()
                ->message()
                ->create([
                    'sender_id' => $user->id,
                    'content' => $request->content,
                    'chat_id' => $request->chat_id
                ]);

            event(new NewMessageSent($message));
            Log::info('Message sent and broadcasted: ', ['message' => $message]);

            DB::commit();
            return $this->showResponse(
                $message,
                'chat.message_sent'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->showError(
                $e,
                'chat.errors.message_error'
            );
        }
    }
}
