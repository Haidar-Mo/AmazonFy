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
            'order' => [
                'id' => $this->order->id,
                'status' => 'pending',
                'shop' => $this->order->shop?->only(['id', 'name']),
                'client' => $this->order->client?->only(['id', 'name']),
                'product' => $this->order->product?->only(['id', 'name']),
            ],
            'icon' => 'green_check'
        ];
    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
