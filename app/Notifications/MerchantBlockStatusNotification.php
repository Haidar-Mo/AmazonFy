<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class MerchantBlockStatusNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $merchant, public string $status)
    {
        $this->notType = 'user' ;
        $this->model = $merchant;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
