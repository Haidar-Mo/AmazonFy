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
        $this->notType = 'merchant_documentation_' . $this->status;

        $this->body = [
            'merchant' => $this->merchant,
            'status' => $this->status, // accepted or declined
        ];
    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
