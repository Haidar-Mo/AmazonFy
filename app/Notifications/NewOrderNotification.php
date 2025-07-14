<?php

namespace App\Notifications;

use App\Models\ShopOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrderNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public ShopOrder $order)
    {
        $this->notType = 'new_invoice_request';

        $this->order->load(['shop', 'client', 'product']);

        $this->body = [
            'order' => $this->order
        ];
    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
