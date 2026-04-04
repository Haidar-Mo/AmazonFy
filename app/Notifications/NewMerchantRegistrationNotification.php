<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewMerchantRegistrationNotification extends BaseNotification implements ShouldQueue
{
    //Do: I removed the "implements ShouldQueue" because I want to send this notification immediately without queuing it
    //: This is because there is a problem in queueing the email.
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $merchant)
    {
        $this->notType = 'user';
        $this->model = $merchant;
        $this->notification_name = "new_merchant_registration";

    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }
}
