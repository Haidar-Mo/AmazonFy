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
        $this->notType = 'merchant_' . $this->status;

        $this->body = [
            'merchant' => [
                'id' => $this->merchant->id,
                'name' => $this->merchant->name,
                'email' => $this->merchant->email,
            ],
            'status' => $this->status, // blocked or unblocked
        ];
    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
