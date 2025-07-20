<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class MerchantDocumentationReviewNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $merchant, public string $status)
    {
        $this->notType = 'user';
        $this->model = $merchant;
        $this->notification_name = "merchant_documentation_$status";
    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
