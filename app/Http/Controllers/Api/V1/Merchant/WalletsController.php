<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Merchant\ChargeBalanceRequest;
use App\Http\Requests\Api\V1\Merchant\WithdrawBalanceRequest;
use App\Models\Address;
use App\Models\User;
use App\Models\Wallet;
use App\Notifications\NewTransactionNotification;
use App\Traits\HasFiles;
use App\Traits\ResponseTrait;
use Auth;
use DB;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Notification;

class WalletsController extends Controller
{
    use ResponseTrait, HasFiles;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $wallet = Auth::user()->wallet;
        $data = $wallet->load('walletAddress');
        return $this->showResponse($data, 'wallet.show_success');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wallet $wallet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        //
    }

    public function chargeBalance(ChargeBalanceRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $wallet = Auth::user()->wallet;
            $image_path = $this->saveFile($request->image, 'Transactions/Charges');

            $wallet->transactionHistories()->create(
                array_merge(
                    $request->validated(),
                    [
                        'transaction_type' => 'charge',
                        'image' => $image_path
                    ]
                )
            );

            $usersWithRoles = User::role(['admin', 'supervisor'], 'api')->get();
            Notification::send($usersWithRoles, new NewTransactionNotification($request->user()));


            return $this->showMessage('wallet.charge_create');
        });
    }


    public function withdrawBalance(WithdrawBalanceRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $wallet = Auth::user()->wallet;
            $wallet->transactionHistories()->create(array_merge(
                $request->validated(),
                [
                    'transaction_type' => 'withdraw',
                ]
            ));

            $usersWithRoles = User::role(['admin', 'supervisor'], 'api')->get();
            Notification::send($usersWithRoles, new NewTransactionNotification($request->user()));

            return $this->showMessage('wallet.withdraw_create');
        });
    }


    public function indexAllAdminAddresses()
    {
        try {
            return $this->showResponse(Address::all());
        } catch (\Exception $e) {
            return $this->showError($e, 'address.index_error');
        }
    }
}
