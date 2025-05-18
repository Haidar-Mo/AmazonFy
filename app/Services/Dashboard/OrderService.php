<?php

namespace App\Services\Dashboard;

use App\Models\ShopOrder;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatusEnum;
/**
 * Class OrderService.
 */
class OrderService
{
    public function index()
    {
        $orders = ShopOrder::with(['shop', 'product'])->get()
            ->append([
                'shop_name',
                'merchant_name',
                'client_name',
                'client_email',
                'client_phone_number',
                'client_address',
                'client_region',
                'created_from',
            ]);
        $orders->each(function ($order) {
            $order->product->append('full_path_image');
        });
        return $orders;
    }


    public function show(string $id)
    {
        $order = ShopOrder::with(['shop', 'product'])->findOrFail($id)
            ->append([
                'shop_name',
                'merchant_name',
                'client_name',
                'client_email',
                'client_phone_number',
                'client_address',
                'client_region',
                'created_from'
            ]);
        $order->product->append('full_path_image');
        return $order;
    }

    public function destroy(string $id)
    {
        $order = ShopOrder::findOrFail($id);
        DB::transaction(function () use ($order) {
            $order->delete();
        });
    }


    public function updateStatus(string $id)
    {
        $order = ShopOrder::findOrFail($id);
        return match ($order->status) {
            'checking' => $this->makePreparing($order),
            'preparing' => $this->makeDelivered($order),
        };
    }


    public function cancelOrder(string $id)
    {
        $order = ShopOrder::findOrFail($id);
        return DB::transaction(function () use ($order) {
            $order->update(['status' => OrderStatusEnum::CANCELED]);
            //Update Balance 
            return $order;
        });

    }


    //! Helper functions
    private function makePreparing(ShopOrder $order)
    {
        return DB::transaction(function () use ($order) {
            $order->update(['status' => OrderStatusEnum::PREPARING]);
            return $order;

        });

    }
    private function makeDelivered(ShopOrder $order)
    {
        return DB::transaction(function () use ($order) {
            $order->update(['status' => OrderStatusEnum::DELIVERED]);
            return $order;
            //Wallet balance recharge
        });

    }
}
