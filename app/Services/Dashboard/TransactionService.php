<?php

namespace App\Services\Dashboard;

use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class TransactionService.
 */
class TransactionService
{

    public function index()
    {
        return TransactionHistory::where('status', '=', 'pending')->get()->each(function ($transaction) {
            $transaction->append('user_name', 'user_phone_number', 'image_full_path');
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
            'point' => 'required_if:decision,approve'
        ]);
        $transaction = TransactionHistory::findOrFail($id);

        if ($transaction->status != 'pending')
            throw new \Exception('هذه العاملة قد تمت معالجتها بالفعل', 400);
        $wallet = $transaction->wallet()->first();

        return DB::transaction(function () use ($data, $wallet, $transaction) {
            match ($data['decision']) {
                'approve' => tap($transaction, function () use ($data, $wallet, $transaction) {
                        if ($transaction->transaction_type === 'charge') {
                            $wallet->total_balance += $data['point'];
                            $wallet->available_balance += $data['point'];
                            $wallet->save();
                        } elseif ($transaction->transaction_type === 'withdraw') {
                            $wallet->total_balance -= $data['point'];
                            $wallet->available_balance -= $data['point'];
                            $wallet->save();
                        }
                        $transaction->update([
                        'status' => 'approved'
                        ]);
                    }),
                'reject' => $transaction->update(['status' => 'rejected']),
                default => null
            };

            return $transaction->load('wallet');
        });

    }


    public function createTransaction(Request $request, string $id)
    {
        $request->validate([
            'type' => 'required|in:charge,withdraw',
            'point' => 'required|numeric'
        ]);
        $wallet = User::findOrFail($id)->wallet()->first();

        match ($request->type) {
            'charge' =>
            DB::transaction(function () use ($request, $wallet) {
                    $wallet->update([
                    'available_balance' => $wallet->available_balance += $request->point,
                    'total_balance' => $wallet->total_balance += $request->point
                    ]);

                    //? Create transaction ??
    
                    return $wallet;
                }),
            'withdraw' =>
            DB::transaction(function () use ($request, $wallet) {
                    //!! Check Available balance
                    
                    $wallet->update([
                    'available_balance' => $wallet->available_balance += $request->point,
                    'total_balance' => $wallet->total_balance += $request->point
                    ]);

                    //? Create transaction ??
    

                    return $wallet;
                }),
        };
    }
}
