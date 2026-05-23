<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Events\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CreateChatRequest;
use App\Http\Requests\Api\V1\CreateMessageRequest;
use App\Http\Resources\ChatResource;
use App\Models\Chat;
use App\Services\Dashboard\ChatsAndMessagesService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatsAndMessagesController extends Controller
{
    use ResponseTrait;

    public function __construct(protected ChatsAndMessagesService $service)
    {
    }

    public function indexChats(Request $request)
    {
        try {
            $chats = Chat::latest('updated_at')->get()->each(function ($chat) {
                $chat->append(['merchant_name']);
            });

            return $this->showResponse(
                $chats,
                'chat.index_success'
            );
        } catch (\Exception $e) {
            return $this->showError(
                $e,
                'chat.errors.index_error'
            );
        }
    }

    public function showChat(string $id)
    {
        try {
            $chat = Chat::with(['user.shop', 'message'])
                ->findOrFail($id);

            $chat->message()
                ->where('sender_id', '!=', request()->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return $this->showResponse(
                new ChatResource($chat),
                'chat.show_success'
            );
        } catch (\Exception $e) {
            return $this->showError(
                $e,
                'chat.errors.show_error'
            );
        }
    }

    public function storeChat(CreateChatRequest $request)
    {
        DB::beginTransaction();
        try {
            $chat = Chat::firstOrCreate([
                'user_id' => $request->user_id,
            ]);

            DB::commit();
            return $this->showResponse(
                $chat,
                'chat.create_success'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->showError(
                $e,
                'chat.errors.create_error'
            );
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->destroyChat($id);
            return $this->showMessage('chat.delete_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'chat.delete_error');
        }
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