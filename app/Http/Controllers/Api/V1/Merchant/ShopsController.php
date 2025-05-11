<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Merchant\StoreShopRequest;
use App\Http\Requests\Api\V1\Merchant\UpdateShopRequest;
use App\Models\Shop;
use App\Models\ShopType;
use App\Traits\HasFiles;
use App\Traits\ResponseTrait;
use Auth;
use DB;
use Illuminate\Http\Request;

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
            'message' => 'success',
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
            ]);

            return $this->showResponse($shop, status: 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        return $this->showResponse($shop);
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
    public function update(UpdateShopRequest $request, Shop $shop)
    {
        return DB::transaction(function () use ($request, $shop) {
            $user = Auth::user();
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
            return $this->showResponse($shop);
        });

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        return DB::transaction(function () use ($shop) {
            $this->deleteFile($shop->logo);
            $this->deleteFile($shop->identity_front_face);
            $this->deleteFile($shop->identity_back_face);
            $shop->delete();
            return $this->showMessage('Deleted Successfully');
        });
    }
}
