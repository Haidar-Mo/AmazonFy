<?php

namespace App\Notifications;

use App\Models\ShopOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCanceledNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public ShopOrder $order)
    {
        $this->notType = "order";
        $this->model = $order;
        $this->notification_name = "order_canceled";
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
