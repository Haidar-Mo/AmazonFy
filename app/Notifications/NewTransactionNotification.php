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

        /*$this->user->load('wallet.transactionHistories');

        $this->body = [
            'user' => $this->user,
            'wallet' => $this->user->wallet?->only([
                'id',
                'available_balance',
                'marginal_balance',
                'total_balance',
            ]),
            'transactions' => $this->user->wallet?->transactionHistories->map(function ($txn) {
                return $txn->only([
                    'id',
                    'amount',
                    'transaction_type',
                    'target',
                    'charge_network',
                    'coin_type',
                    'status',
                ]);
            }),
        ];
 */
    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
