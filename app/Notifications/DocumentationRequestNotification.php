<?php

namespace App\Notifications;

use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;


class DocumentationRequestNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Shop $shop)
    {
        $this->notType = 'shop';
        $this->model = $shop;
        $this->notification_name = 'documentation_request';
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
