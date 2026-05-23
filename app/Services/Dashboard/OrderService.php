<?php

namespace App\Services\Dashboard;

use App\Models\Client;
use App\Models\ShopOrder;
use App\Models\Wallet;
use App\Notifications\OrderCanceledNotification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\Notification;


/**
 * Class OrderService.
 */
class OrderService
{
    public function index()
    {
        $orders = ShopOrder::with(['shop', 'product'])
            ->whereNotIn('status', [OrderStatus::PENDING->value, OrderStatus::DELIVERED->value, OrderStatus::CANCELED->value])
            ->latest()
            ->get()
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


    public function createOrder(FormRequest $request)
    {
        $client_data = [
            'region_id' => $request->region_id,
            'name' => $request->name,
            'email' => $request->email ?? '',
            'phone_number' => $request->phone_number ?? '',
            'address' => $request->address ?? '',
        ];
        DB::transaction(function () use ($request, $client_data) {

            $client = Client::firstOrCreate($client_data, $client_data);

            $orders_data = $request->orders;

            foreach ($orders_data as $order) {
                $order['total_price'] = $order['selling_price'] * $order['count'];
                $client->orders()->create($order);
            }
            unset($order);

        });
    }

    public function cancel(string $id)
    {
        return DB::transaction(function () use ($id) {
            $order = ShopOrder::findOrFail($id);
            if ($order->status != OrderStatus::CHECKING->value) {
                throw new \Exception(__('messages.wallet.errors.cancel_deny'), 400);
            }

            $user = $order->shop->user;

            $merchant_wallet = $user->wallet()->lockForUpdate()->first();

            $order = $this->makeCancel($order, $merchant_wallet);
            Notification::send($user, new OrderCanceledNotification($order));
            return $order;
        });
    }


    public function updateStatus(string $id)
    {
        return DB::transaction(function () use ($id) {
            $order = ShopOrder::findOrFail($id);

            if (in_array($order->status, [OrderStatus::PENDING->value, OrderStatus::DELIVERED->value, OrderStatus::CANCELED->value])) {
                throw new \Exception(__('messages.wallet.errors.update_deny'), 400);
            }
            $merchant_wallet = $order->shop->user->wallet()->lockForUpdate()->first();

            return match ($order->status) {
                'checking' => $this->makePreparing($order),
                'preparing' => $this->makeDelivered($order, $merchant_wallet),
            };
        });
    }

    public function updateManyStatuses(array $ids)
    {
        return DB::transaction(function () use ($ids) {
            $updatedOrders = [];

            foreach ($ids as $id) {
                $order = ShopOrder::findOrFail($id);

                if (in_array($order->status, [OrderStatus::PENDING->value, OrderStatus::DELIVERED->value, OrderStatus::CANCELED->value])) {
                    throw new \Exception(__('messages.wallet.errors.update_deny') . " (ID: {$id})", 400);
                }

                $merchant_wallet = $order->shop?->user->wallet()->lockForUpdate()->first();

                $updatedOrders[] = match ($order->status) {
                    'checking' => $this->makePreparing($order),
                    'preparing' => $this->makeDelivered($order, $merchant_wallet),
                };
            }

            return $updatedOrders;
        });
    }

    //! Helper functions
    private function makePreparing(ShopOrder $order)
    {
        $order->update(['status' => OrderStatus::PREPARING->value]);
        return $order;
    }

    private function makeDelivered(ShopOrder $order, Wallet $wallet)
    {
        $cost = $order->wholesale_price * $order->count;
        $revenue = $order->selling_price * $order->count;

        if ($wallet->marginal_balance < $cost) {
            throw new \Exception(__('wallet.errors.margin_insufficient_funds'), 400);
        }

        $order->update(['status' => OrderStatus::DELIVERED->value]);
        $wallet->update([
            'marginal_balance' => $wallet->marginal_balance - $cost,
            'available_balance' => $wallet->available_balance + $revenue,
            'total_balance' => $wallet->total_balance + ($revenue - $cost),
        ]);
        return $order;
    }

    private function makeCancel(ShopOrder $order, Wallet $wallet)
    {
        $cost = $order->wholesale_price * $order->count;

        if ($wallet->marginal_balance < $cost) {
            throw new \Exception(__('wallet.errors.margin_insufficient_funds'), 400);
        }

        $order->update(['status' => OrderStatus::CANCELED->value]);
        $wallet->update([
            'marginal_balance' => $wallet->marginal_balance - $cost,
            'available_balance' => $wallet->available_balance + $cost,
        ]);
        return $order;
    }
}
