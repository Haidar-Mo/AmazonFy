<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Filters\OrdersFilters;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopOrder;
use App\Traits\ResponseTrait;
use Auth;
use DB;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected OrdersFilters $ordersFilters,
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = $this->ordersFilters->applyFilters(ShopOrder::query())
            ->with(['product', 'client'])
            ->where('shop_id', Auth::user()->shop->id)
            ->get()
            ->append('total_profit')
            ->each(function ($order) {
                $order->product?->append('full_path_image');
            });
        return $this->showResponse($orders, __('messages.order.index_success'));
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
        return DB::transaction(function () use ($request, $shopOrder) {
            $request->validate(['accepted' => ['required', 'boolean']]);

            $shop = Auth::user()->shop;
            if ($shop->user_id != $request->user()->id || $shopOrder->shop_id != $shop->id || $shopOrder->status != 'pending') {
                throw new AuthorizationException();
            }
            if ($request->accepted) {
                $wallet = Auth::user()->wallet;
                if ($wallet->available_balance < $shopOrder->total_price) {
                    return $this->showMessage(__('messages.wallet.errors.insufficient_funds'), [], 400, false);
                }
                $wallet->available_balance -= $shopOrder->total_price;
                $wallet->marginal_balance += $shopOrder->total_price;
                $wallet->save();
                $shopOrder->update(['status' => 'checking']);
            } else {
                $shopOrder->update(['status' => 'canceled']);
            }
            $shopOrder->save();
            return $this->showMessage(__('messages.order.update_success'));

        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShopOrder $shopOrder)
    {
        //
    }
}
