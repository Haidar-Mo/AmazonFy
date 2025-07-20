<?php

namespace App\Notifications;

use App\Models\ShopOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrderNotification extends BaseNotification
{
    use Queueable;

    public function __construct(public ShopOrder $order)
    {
        $this->notType = 'order';
        $this->model = $order;
        $this->notification_name = "new_invoice_request";


    }

    public function via($notifiable): array
    {
        return ['database'];
    }

}
