<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\NewMessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CreateMessageRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    use ResponseTrait;

    public function index()
    {
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMessageRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $request->user();
            $message = $user->chats()->where('id', $request->chat_id)->firstOrFail()->messages()->create([
                'sender_id' => $user->id,
                'content' => $request->content,
                'chat_id' => $request->chat_id
            ]);
            event(new NewMessageSent($message));
            Log::info('Message sent and broadcasted: ', ['message' => $message]);
            DB::commit();
            return $this->showMessage('Message sent..!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->showError($e, 'something goes wrong...!');
        }
    }
}
