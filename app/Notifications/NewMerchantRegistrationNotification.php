<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMerchantRegistrationNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $merchant)
    {
        $this->notType = 'new_merchant_registration';

        $this->body = [
            'merchant' => [
                'id' => $this->merchant->id,
                'name' => $this->merchant->name,
                'email' => $this->merchant->email,
                // Add more as needed
            ]
        ];
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
