<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Merchant\ChargeBalanceRequest;
use App\Http\Requests\Api\V1\Merchant\WithdrawBalanceRequest;
use App\Models\Wallet;
use App\Traits\HasFiles;
use App\Traits\ResponseTrait;
use DB;
use Illuminate\Http\Request;

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
    public function show(Wallet $wallet)
    {
        $data = $wallet->load('addresses');
        return $this->showResponse($data);
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

    public function chargeBalance(ChargeBalanceRequest $request, Wallet $wallet)
    {
        return DB::transaction(function () use ($request, $wallet) {

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

            DB::afterCommit(function () {
                // send the notification here
            });

            return $this->showMessage('Operation Successded');
        });
    }


    public function withdrawBalance(WithdrawBalanceRequest $request, Wallet $wallet)
    {
        return DB::transaction(function () use ($request, $wallet) {

            $wallet->transactionHistories()->create(array_merge(
                $request->validated(),
                [
                    'transaction_type' => 'withdraw',
                ]
            ));
            DB::afterCommit(function () {
                // send the notification here
            });
            return $this->showMessage('Operation Successded');
        });
    }

}
