<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Client\StoreRateRequest;
use App\Models\Client;
use App\Models\Shop;
use App\Traits\ResponseTrait;
use DB;
use Illuminate\Http\Request;

class RatesController extends Controller
{
    use ResponseTrait;

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
    public function store(StoreRateRequest $request, Shop $shop)
    {
        return DB::transaction(function () use ($request, $shop) {

            if ($request->email) {
                $client = Client::firstWhere('email', $request->email);
            } else {
                $client = Client::firstWhere('phone_number', $request->phone_number);
            }

            $client->rates()->create([
                'rate' => $request->rate,
                'shop_id' => $shop->id,
            ]);

            // Calculate new average rating and update shop
            $shop->update([
                'rate' => $shop->rates()->avg('rate') ?? 0
            ]);

            return $this->showMessage('api.success');
        });

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
