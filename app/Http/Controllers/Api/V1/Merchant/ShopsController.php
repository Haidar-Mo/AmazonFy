<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Merchant\StoreShopRequest;
use App\Http\Requests\Api\V1\Merchant\UpdateShopRequest;
use App\Models\Shop;
use App\Models\ShopType;
use App\Models\User;
use App\Notifications\DocumentationRequestNotification;
use App\Traits\HasFiles;
use App\Traits\ResponseTrait;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ShopsController extends Controller
{
    use HasFiles, ResponseTrait;
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
        $types = ShopType::get(['id', 'name']);
        return response()->json([
            'message' => __('messages.shop_type.show_success'),
            'types' => $types
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $logo_path = $this->saveFile($request->logo, 'Shop-Logos');
            $identity_front_face_path = $this->saveFile($request->identity_front_face, 'Identities/Front-Face');
            $identity_back_face_path = $this->saveFile($request->identity_back_face, 'Identities/Back-Face');

            $shop = Shop::create([
                'name' => $request->name,
                'user_id' => $user->id,
                'phone_number' => $request->phone_number,
                'shop_type_id' => $request->shop_type_id,
                'logo' => $logo_path,
                'identity_number' => $request->identity_number,
                'identity_front_face' => $identity_front_face_path,
                'identity_back_face' => $identity_back_face_path,
                'address' => $request->address,
                'representative_code' => $request->representative_code
            ]);

            $admin = User::role('admin', 'api')->first(); //! set the proper admin id
            Notification::send($admin, new DocumentationRequestNotification($shop));

            return $this->showResponse($shop->append(['logo_full_path', 'identity_front_face_full_path', 'identity_back_face_full_path', 'type_name']), __('messages.shop.store_success'), status: 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $shop = Auth::user()->shop;
        if (request()->has('l') && request()->query('l') === 'home') {
            $data = $shop->withCount(['products', 'shopOrders'])->where('user_id', Auth::user()->id)->get()->append(['logo_full_path', 'identity_front_face_full_path', 'identity_back_face_full_path', 'type_name']);
        } else {
            $data = $shop->withCount(['products', 'shopOrders'])
                ->with([
                    'products' => function ($query) {
                        // No append here - we'll handle it later
                    }
                ])
                ->where('user_id', Auth::user()->id)
                ->get()
                ->map(function ($shop) {
                    // Append shop attributes
                    $shop->append([
                        'logo_full_path',
                        'identity_front_face_full_path',
                        'identity_back_face_full_path',
                        'type_name'
                    ]);

                    // Append full_path_image to each product
                    $shop->products->each->append('full_path_image');

                    return $shop;
                });
        }
        return $this->showResponse($data, __('messages.shop.show_success'));
    }

    public function getStatistics()
    {
        $shop = Shop::where('user_id', Auth::id())
            ->withCount([
                'products',
                'shopOrders',
                'shopOrders as daily_orders_count' => function ($query) {
                    $query->whereDate('created_at', today());
                }
            ])
            ->withSum([
                'shopOrders as daily_sales' => function ($query) {
                    $query->whereDate('created_at', today())
                        ->where('status', 'delivered');
                },
                'shopOrders as total_sales' => function ($query) {
                    $query->where('status', 'delivered');
                }
            ], 'selling_price')
            ->withSum([
                'shopOrders as daily_wholesale' => function ($query) {
                    $query->whereDate('created_at', today())
                        ->where('status', 'delivered');
                },
                'shopOrders as total_wholesale' => function ($query) {
                    $query->where('status', 'delivered');
                }
            ], 'wholesale_price')
            ->first();

        if ($shop) {
            // Convert null sums to 0
            $sumAttributes = [
                'daily_sales',
                'total_sales',
                'daily_wholesale',
                'total_wholesale'
            ];

            foreach ($sumAttributes as $attribute) {
                $shop->{$attribute} = $shop->{$attribute} ?? 0;
            }

            // Calculate profits
            $shop->daily_profit = $shop->daily_sales - $shop->daily_wholesale;
            $shop->total_profit = $shop->total_sales - $shop->total_wholesale;

            // Append accessors
            $shop->append([
                'logo_full_path',
                'identity_front_face_full_path',
                'identity_back_face_full_path',
                'type_name'
            ]);
        }

        return $this->showResponse($shop, 'messages.statistics.success');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShopRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = Auth::user();
            $shop = $user->shop;
            $attributes = $request->validated();
            if ($request->logo) {
                $this->deleteFile($shop->logo);
                $logo_path = $this->saveFile($request->logo, 'Shop-Logos');
                $attributes['logo'] = $logo_path;
            }
            if ($request->identity_front_face) {
                $this->deleteFile($shop->identity_front_face);
                $identity_front_face_path = $this->saveFile($request->identity_front_face, 'Identities/Front-Face');
                $attributes['identity_front_face'] = $identity_front_face_path;
            }
            if ($request->identity_back_face) {
                $this->deleteFile($shop->identity_back_face);
                $identity_back_face_path = $this->saveFile($request->identity_back_face, 'Identities/Back-Face');
                $attributes['identity_back_face'] = $identity_back_face_path;
            }
            $shop->update($attributes);

            return $this->showResponse(
                $shop->append(['logo_full_path', 'identity_front_face_full_path', 'identity_back_face_full_path', 'type_name']),
                'messages.shop.update_success'
            );
        });

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        return DB::transaction(function () {
            $shop = Auth::user()->shop;
            $this->deleteFile($shop->logo);
            $this->deleteFile($shop->identity_front_face);
            $this->deleteFile($shop->identity_back_face);
            $shop->delete();
            return $this->showMessage('messages.shop.delete_success');
        });
    }
}
