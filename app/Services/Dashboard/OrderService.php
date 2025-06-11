<?php

namespace App\Services\Dashboard;

use App\Models\ShopOrder;
use App\Models\Wallet;
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
            $order->product?->append('full_path_image');
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
        $order->product?->append('full_path_image');
        return $order;
    }

    public function cancel(string $id)
    {
        $order = ShopOrder::findOrFail($id);
        if (in_array($order->status, ['pending', 'delivered', 'canceled']))
            throw new \Exception('can not cancel this order', 400);

        $merchant_wallet = $order->shop()->first()
            ->user()->first()
            ->wallet()->first();

        return $this->makeCancel($order, $merchant_wallet);
    }


    public function updateStatus(string $id)
    {
        $order = ShopOrder::findOrFail($id);

        $merchant_wallet = $order->shop()->first()
            ->user()->first()
            ->wallet()->first();

        return match ($order->status) {
            'checking' => $this->makePreparing($order),
            'preparing' => $this->makeDelivered($order, $merchant_wallet),
        };
    }

    //! Helper functions
    private function makePreparing(ShopOrder $order)
    {
        return DB::transaction(function () use ($order) {
            $order->update(['status' => OrderStatusEnum::PREPARING]);
            return $order;

        });

    }
    private function makeDelivered(ShopOrder $order, Wallet $wallet)
    {
        return DB::transaction(function () use ($order, $wallet) {
            $order->update(['status' => OrderStatusEnum::DELIVERED]);
            $wallet->update([
                'marginal_balance' => $wallet->marginal_balance - $order->wholesale_price * $order->count,
                'available_balance' => $wallet->available_balance + $order->selling_price * $order->count,
                'total' => $wallet->total_balance + ($order->selling_price * $order->count) - ($order->wholesale_price * $order->count),
            ]);

            return $order;
        });

    }

    private function makeCancel(ShopOrder $order, Wallet $wallet)
    {
        return DB::transaction(function () use ($order, $wallet) {
            $order->update(['status' => OrderStatusEnum::CANCELED]);
            $wallet->update([
                'marginal_balance' => $wallet->marginal_balance - $order->wholesale_price * $order->count,
                'available_balance' => $wallet->available_balance + $order->wholesale_price * $order->count,
            ]);
            return $order;
        });
    }
}
