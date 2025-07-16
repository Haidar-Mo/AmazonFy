<?php

namespace App\Services\Dashboard;

use App\Models\Client;
use App\Models\Product;
use App\Models\Shop;
use App\Models\ShopOrder;
use App\Models\ShopType;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Class StatisticsService.
 */
class StatisticsService
{
    public function statistics(Request $request)
    {
        $date_1 = $request->input('first_date');
        $date_2 = $request->input('second_date');
        $type = $request->input('type', 'yearly');

        $groupByFormat = match ($type) {
            'weekly' => 'WEEK',
            'monthly' => 'MONTH',
            'yearly' => 'YEAR',
            default => 'YEAR(created_at)',
        };



        //- Group orders by time-range
        $orders_count = ShopOrder::whereBetween('created_at', [$date_1, $date_2])
            ->select(
                DB::raw("$groupByFormat(created_at) as period"),
                DB::raw('count(*) as count')
            )
            ->groupBy('period')
            ->get()
            ->map(fn($item) => [
                'date' => strval($item->period),
                'total_orders' => $item->count,
            ]);


        //- Group clients by time-range
        $all_clients = Client::whereBetween('created_at', [$date_1, $date_2])
            ->select(DB::raw("$groupByFormat(created_at) as period"), DB::raw('count(*) as count'))
            ->groupBy('period')
            ->get();

        $clients_count = $all_clients->map(fn($item) => [
            'date' => strval($item->period),
            'total_clients' => $item->count,
        ]);


        //- Group Shops by Status;
        $shops_count = Shop::count();
        $verified_count = Shop::where('status', '=', 'active')->count();
        $unverified_count = $shops_count - $verified_count;

        $verified_shops_ratio = $shops_count > 0 ? ($verified_count / $shops_count) * 100 : 0;
        $unverified_shops_ratio = $shops_count > 0 ? ($unverified_count / $shops_count) * 100 : 0;

        $shops_ratios = (object) [
            'verified_shops_ratio' => round($verified_shops_ratio, 2),
            'unverified_shops_ratio' => round($unverified_shops_ratio, 2),
        ];

        //- Number of Products
        $products_count = Product::all()->count();


        //- Number of blocked account
        $blocked_accounts_count = User::where('is_blocked', '=', 1)->count();


        //- the most product sold
        $most_sold_product = Product::query()
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->first()
            ->append('full_path_image')
            ->makeHidden(['details', 'type', 'image', 'wholesale_price', 'selling_price', 'is_available', "created_at", "updated_at", "deleted_at",]);

        //- The most 10 selling shops
        $most_selling_shops = Shop::withCount('shopOrders')
            ->orderBy('shop_orders_count', 'desc')
            ->limit(10)
            ->get()
            ->append('logo_full_path')
            ->makeHidden(['user_id', 'shop_type_id', 'phone_number', 'identity_number', 'logo', 'identity_front_face', 'identity_back_face', 'address', 'status', 'rate', "created_at", "updated_at"]);


        $type_statistics = Product::whereHas('orders')
            ->withCount('orders')
            ->join('shop_orders', 'products.id', '=', 'shop_orders.product_id')
            ->join('product_types', 'products.type_id', '=', 'product_types.id')
            ->whereBetween('shop_orders.created_at', [$date_1, $date_2])
            ->select([
                DB::raw("$groupByFormat(shop_orders.created_at) as period"),
                DB::raw("count(*) as count")
            ])->groupBy(['period'])
            ->limit(3)
            ->get();

        //- Financial statistic group by time-range
        $financial_statistics = ShopOrder::where('status', '!=', 'pending')
            ->whereBetween('created_at', [$date_1, $date_2])
            ->select(
                DB::raw("$groupByFormat(created_at) as period"),
                DB::raw("COALESCE(SUM(wholesale_price), 0) as total_benefits")
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // $total_benefits = $orders->sum('total_benefits');

        return [
            'orders_count' => $orders_count,    //-done
            'client_count' => $clients_count,   //-done

            'shops_count_statistic' => $shops_ratios, //- done

            'products_count' => $products_count, //- done

            'blocked_accounts_count' => $blocked_accounts_count,//- done

            'most_sold_product' => $most_sold_product,  //-done

            'most_selling_shops' => $most_selling_shops, //-done

            'type_statistics' => $type_statistics, //- done

            'financial_statistics' => $financial_statistics, //- done
        ];
    }
}
