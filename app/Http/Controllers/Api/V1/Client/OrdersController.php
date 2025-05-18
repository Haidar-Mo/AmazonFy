<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Client\StoreOrderRequest;
use App\Models\ShopOrder;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
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
        //
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
