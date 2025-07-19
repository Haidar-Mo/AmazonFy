<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Resources\NotificationResource;
use App\Traits\ResponseTrait;

class NotificationController
{

    use ResponseTrait;

    public function index()
    {
        $notifications = auth()->user()->notifications;
        return $this->showResponse(NotificationResource::collection($notifications));
    }

}