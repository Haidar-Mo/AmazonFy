<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WalletAddress;
use App\Traits\ResponseTrait;
use Auth;
use DB;
use Illuminate\Http\Request;

class WalletAddressesController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Wallet $wallet)
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
        $old_address = auth()->user()->wallet->walletAddress()->where('name', '=', $request->name)->first();
        if ($old_address)
            return $this->showMessage('you cant create more than one Address for the same network name', 400, false);
        return DB::transaction(function () use ($request) {
            $request->validate([
                'name' => ['required', 'string'],
                'target' => ['required', 'string']
            ]);


            $wallet = Auth::user()->wallet;
            $wallet->walletAddress()->create($request->all());
            return $this->showResponse($wallet->load('walletAddress'));

        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Wallet $wallet, WalletAddress $walletAddress)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wallet $wallet, WalletAddress $walletAddress)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WalletAddress $walletAddress)
    {
        $wallet = Auth::user()->wallet;
        $address = $wallet->walletAddress()->findOrFail($walletAddress->id);
        return DB::transaction(function () use ($request, $wallet, $address) {
            $request->validate([
                'name' => ['string', 'unique:wallet_addresses,name,' . $address->id],
                'target' => ['string']
            ],['name.unique'=>'you cant create more than one Address for the same network name']);
            $address->update($request->all());
            $address->save();
            return $this->showResponse($wallet->load('walletAddress'));
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WalletAddress $walletAddress)
    {
        return DB::transaction(function () use ($walletAddress) {
            $wallet = Auth::user()->wallet;
            $address = $wallet->walletAddress()->findOrFail($walletAddress->id);
            $address->delete();
            return $this->showMessage('Deleted successfully');
        });

    }
}
