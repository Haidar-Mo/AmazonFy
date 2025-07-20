<?php

namespace App\Notifications;

use App\Models\TransactionHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewTransactionNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public TransactionHistory $user)
    {
        $this->notType = 'transaction';
        $this->model = $user;
        $this->notification_name = "new_transaction";
    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
