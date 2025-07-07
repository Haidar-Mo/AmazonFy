<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Client\StoreOrderRequest;
use App\Models\Client;
use App\Models\ShopOrder;
use App\Notifications\NewOrderNotification;
use App\Traits\ResponseTrait;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class OrdersController extends Controller
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
    public function store(StoreOrderRequest $request)
    {
        return DB::transaction(function () use($request) {
            $client_data = [
                'region_id' => $request->region_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
            ];

            $client = Client::firstOrCreate($client_data, $client_data);

            $orders_data = $request->orders;

            foreach ($orders_data as &$order) {
                $order['total_price'] = $order['selling_price'] * $order['count'];
            }
            unset($order);

            $orders = $client->orders()->createMany($orders_data);

            foreach ($orders as $order) {
                $shop = $order->shop;
                $merchant = $shop->user;
                Notification::send($merchant, new NewOrderNotification($order));
            }

            return $this->showMessage('api.success');

        });
    }

    /**
     * Display the specified resource.
     */
    public function show(ShopOrder $shopOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShopOrder $shopOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShopOrder $shopOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopOrder $shopOrder)
    {
        //
    }
}
