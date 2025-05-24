<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_image' => $this->user->shop->logo_full_path,
            'message' => MessageResource::collection($this->message),
        ];
    }
}