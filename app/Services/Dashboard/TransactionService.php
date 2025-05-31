<?php

namespace App\Services\Dashboard;

use App\Models\TransactionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class TransactionService.
 */
class TransactionService
{

    public function index()
    {
        return TransactionHistory::all()->each(function ($transaction) {
            $transaction->append('user_name', 'user_phone_number');
        });
    }

    public function show(string $id)
    {
        return TransactionHistory::with('wallet.user')->findOrFail($id);
    }

    public function handleTransaction(string $id, Request $request)
    {
        $data = $request->validate([
            'decision' => 'required',
        ]);
        $transaction = TransactionHistory::findOrFail($id);

        if ($transaction->status != 'pending')
            throw new \Exception('هذه العاملة قد تمت معالجتها بالفعل', 400);
        $wallet = $transaction->wallet()->first();

        return DB::transaction(function () use ($data, $wallet, $transaction) {
            match ($data['decision']) {
                'approve' => tap($transaction, function () use ($wallet, $transaction) {
                        if ($transaction->transaction_type === 'charge') {
                            $wallet->total_balance += $transaction->amount;
                            $wallet->available_balance += $transaction->amount;
                            $wallet->save();
                        } elseif ($transaction->transaction_type === 'withdraw') {
                            $wallet->total_balance -= $transaction->amount;
                            $wallet->available_balance -= $transaction->amount;
                            $wallet->save();
                        }
                        $transaction->update([
                        'status' => 'approved'
                        ]);
                    }),
                'reject' => $transaction->update(['status' => 'rejected']),
                default => null
            };

            return $transaction;
        });

    }
}
