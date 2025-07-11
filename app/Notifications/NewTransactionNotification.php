<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewTransactionNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user)
    {
        $this->notType = 'new_transaction';

        $this->user->load('wallet.transactionHistories');

        $this->body = [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'wallet' => $this->user->wallet?->only(['id', 'balance']),
                'transactions' => $this->user->wallet?->transactionHistories->map(function ($txn) {
                    return $txn->only(['id', 'amount', 'type', 'created_at']);
                }),
            ],
        ];
    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
