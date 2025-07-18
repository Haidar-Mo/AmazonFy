<?php

namespace App\Services\Dashboard;

use App\Models\Shop;
use App\Models\User;
use App\Notifications\MerchantDocumentationReviewNotification;
use App\Traits\HasFiles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * Class ShopService.
 */
class ShopService
{
    use HasFiles;

    public function index()
    {
        return Shop::with('user')
            ->whereIn('status', ['active', 'pending', 'inactive'])
            ->latest()
            ->get()
            ->append(['is_blocked', 'is_blocked_text']);
    }

    public function show(string $id)
    {
        $shop = Shop::with(['user.wallet.walletAddress', 'products', 'shopOrders'])->findOrFail($id)
            ->append([
                'logo_full_path',
                'identity_front_face_full_path',
                'identity_back_face_full_path',
                'type_name',
                'is_blocked',
                'is_blocked_text'
            ]);
        if ($shop->shopOrders) {
            $shop->shopOrders->append([
                'client_name',
                'client_email',
                'client_phone_number',
                'client_address',
                'client_region',
                'created_from'
            ]);
        }
        if ($shop->products) {
            $shop->products->each(function ($product) {
                $product->append(['type_name', 'full_path_image']);
            });
        }
        return $shop;
    }

    public function store(FormRequest $request)
    {
        $data = $request->validated();
        $user = User::findOrFail($data['user_id']);
        if ($user->shop()->first()) {
            throw new \Exception('لا يمكن إنشاء أكثر من متجر لنفس المستخدم', 400);
        }
        $data['status'] = 'active';
        $data['logo'] = $this->saveFile($request->file('logo'), 'Images/Shops/Logo');
        $data['identity_front_face'] = $this->saveFile($request->file('identity_front_face'), 'Images/Shops/Identity');
        $data['identity_back_face'] = $this->saveFile($request->file('identity_back_face'), 'Images/Shops/Identity');
        return DB::transaction(function () use ($data, $user, $request) {
            return $user->shop()->create($data)->append([
                'logo_full_path',
                'identity_front_face_full_path',
                'identity_back_face_full_path',
                'is_blocked',
                'is_blocked_text'
            ]);

        });
    }

    public function update(FormRequest $request, string $id)
    {
        $data = $request->validated();
        $shop = Shop::findOrFail($id);
        if ($request->hasFile('logo')) {
            $this->deleteFile($shop->logo);
            $logo = $this->saveFile($request->file('logo'), 'Images/Shops/Logo');
            $data['logo'] = $logo;
        }
        if ($request->hasFile('identity_front_image')) {
            $this->deleteFile($shop->identity_front_image);
            $identity_front_image = $this->saveFile($request->file('identity_front_image'), 'Images/Shops/Identity');
            $data['identity_front_image'] = $identity_front_image;
        }
        if ($request->hasFile('identity_back_image')) {
            $this->deleteFile($shop->identity_back_image);
            $identity_back_image = $this->saveFile($request->file('identity_back_image'), 'Images/Shops/Identity');
            $data['identity_back_image'] = $identity_back_image;
        }
        return DB::transaction(function () use ($data, $shop) {
            $shop->update($data);
            return $shop->append([
                'logo_full_path',
                'identity_front_face_full_path',
                'identity_back_face_full_path',
                'is_blocked',
                'is_blocked_text'
            ]);
        });
    }

    public function destroy(string $id)
    {
        $shop = Shop::findOrFail($id);
        DB::transaction(function () use ($shop) {
            $shop->delete();
        });
    }


    public function activateShop(string $id)
    {
        $shop = Shop::findOrFail($id);
        $user = $shop->user;
        return DB::transaction(function () use ($shop, $user) {
            $shop->update(['status' => 'active']);
            $shop->append([
                'logo_full_path',
                'identity_front_face_full_path',
                'identity_back_face_full_path',
                'type_name',
                'is_blocked',
                'is_blocked_text'
            ]);
            $user->notify(new MerchantDocumentationReviewNotification($user, 'activate'));
            return $shop;
        });
    }
    public function deactivateShop(string $id)
    {
        $shop = Shop::findOrFail($id);
        $user = $shop->user;
        return DB::transaction(function () use ($shop, $user) {
            $shop->update(['status' => 'inactive']);
            $shop->append([
                'logo_full_path',
                'identity_front_face_full_path',
                'identity_back_face_full_path',
                'type_name',
                'is_blocked',
                'is_blocked_text'
            ]);
            $user->notify(new MerchantDocumentationReviewNotification($user, 'deactivate'));
            return $shop;

        });
    }
}
