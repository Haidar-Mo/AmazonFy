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
            'weekly' => 'WEEK(created_at)',
            'monthly' => 'MONTH(created_at)',
            'yearly' => 'YEAR(created_at)',
            default => 'YEAR(created_at)',
        };



        //- Group orders by time range
        $all_orders = ShopOrder::whereBetween('created_at', [$date_1, $date_2])
            ->select(DB::raw("$groupByFormat as period"), DB::raw('count(*) as count'))
            ->groupBy('period')
            ->get();

        $orders_count = $all_orders->map(fn($item) => [
            'date' => strval($item->period),
            'total_orders' => $item->count,
        ]);


        //- Group clients by time range
        $all_clients = Client::whereBetween('created_at', [$date_1, $date_2])
            ->select(DB::raw("$groupByFormat as period"), DB::raw('count(*) as count'))
            ->groupBy('period')
            ->get();

        $clients_count = $all_clients->map(fn($item) => [
            'date' => strval($item->period),
            'total_clients' => $item->count,
        ]);


        //- Group Merchants by time range
        $Merchants_count_statistic = User::role('merchant', 'api')
            ->whereBetween('created_at', [$date_1, $date_2])
            ->with(['shop'])
            ->select(
                DB::raw("$groupByFormat as period"),
                DB::raw('count(*) as count')
            )
            ->groupBy('period')
            ->get()
            ->map(function ($group) {

                $verified = $group->shop ? 1 : 0;

                return [
                    'date' => strval($group->period),
                    'total_Merchants' => $group->count,
                    'verified_count' => $verified,
                    'unverified_count' => $group->count - $verified,
                ];
            })
            ->values();


        $products_count = Product::all()->count();

        $blocked_accounts_count = User::where('is_blocked', '=', 1)->count();

        $most_sold_product = Product::query()
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->first()
            ->append('full_path_image')
            ->makeHidden(['details', 'type', 'image', 'wholesale_price', 'selling_price', 'is_available', "created_at", "updated_at", "deleted_at",]);


        $most_selling_shops = Shop::withCount('shopOrders')
            ->orderBy('shop_orders_count', 'desc')
            ->limit(10)
            ->get()
            ->append('logo_full_path')
            ->makeHidden(['user_id', 'shop_type_id', 'phone_number', 'identity_number', 'logo', 'identity_front_face', 'identity_back_face', 'address', 'status', 'rate', "created_at", "updated_at"]);


        //- Financial statistic
        $benefits_statistics = ShopOrder::where('status', '!=', 'pending')
            ->whereBetween('created_at', [$date_1, $date_2])
            ->select(
                DB::raw("$groupByFormat as period"),
                DB::raw("COALESCE(SUM(wholesale_price), 0) as total_benefits")
            )
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        // $total_benefits = $orders->sum('total_benefits');

        return [
            'orders_count' => $orders_count,    //-done
            'client_count' => $clients_count,   //-done

            //  'Merchants_count_statistic' => $Merchants_count_statistic,  //! not done

            'products_count' => $products_count, //- done

            'blocked_accounts_count' => $blocked_accounts_count,//- done

            'most_sold_product' => $most_sold_product,  //-done

            'most_selling_shops' => $most_selling_shops,

            //'type_statistics' => $type_statistics, //! not done

            'benefits_statistics' => $benefits_statistics, //- done
        ];
    }
}
