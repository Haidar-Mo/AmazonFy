<?php

namespace App\Services\Dashboard;

use App\Models\TransactionHistory;
use Illuminate\Http\Client\Request;

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
            'type' => 'required',
            'amount' => 'required|decimal'
        ]);

        if ($data['type'] === 'deposit') {
            // Add to wallet
        } elseif ($data['type'] === 'withdraw') {
            // Subtract from wallet
        }
    }
}
