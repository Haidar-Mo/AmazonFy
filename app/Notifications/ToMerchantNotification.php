<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ToMerchantNotification extends BaseNotification
{

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $ar_title, public string $en_title, public string $ar_body, public string $en_body, public Model $model)
    {

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

    public function toArray($notifiable)
    {
        return [
            'en' => [
                'notification_type' => 'custom_notification',
                'title' => $this->en_title,
                'body' => $this->en_body,
                'model_id' => null
            ],
            'ar' => [
                'notification_type' => 'custom_notification',
                'title' => $this->ar_title,
                'body' => $this->ar_body,
                'model_id' => null
            ]
        ];
    }
}
