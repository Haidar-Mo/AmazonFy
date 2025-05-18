<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Traits\HasFiles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * Class MerchantService.
 */
class MerchantService
{
    use HasFiles;

    public function show(string $id)
    {

        $merchant = User::with(['shop.products', 'wallet.addresses', 'shop.orders'])
            ->role('merchant', 'api')
            ->findOrFail($id)
            ->makeVisible(['is_blocked'])
            ->append(['shop_status']);
        if ($merchant->shop) {
            $merchant->shop->append(['logo_full_path', 'identity_front_face_full_path', 'identity_back_face_full_path']);
        }
        return $merchant;
    }

    public function store(FormRequest $request)
    {
        $data = $request->validated();
        return DB::transaction(function () use ($data) {
            $user = User::create($data);
            $user->assignRole(Role::where('name', 'merchant')->where('guard_name', 'api')->first());
            return $user;
        });
    }

    public function update(FormRequest $request, string $id)
    {
        $merchant = User::findOrFail($id);
        $data = $request->validated();
        return DB::transaction(function () use ($merchant, $data) {
            $merchant->update($data);
            return $merchant;
        });
    }

    public function destroy(string $id)
    {
        $merchant = User::role('merchant', 'api')->findOrFail($id);
        DB::transaction(function () use ($merchant) {
            $merchant->delete();
        });
    }

    public function blockMerchant(string $id)
    {
        $merchant = User::role('merchant', 'api')->findOrFail($id);
        return DB::transaction(function () use ($merchant) {
            $merchant->update(['is_blocked' => true]);
            return $merchant->makeVisible(['is_blocked']);
        });
    }

    public function unblockMerchant(string $id)
    {
        $merchant = User::role('merchant', 'api')->findOrFail($id);
        return DB::transaction(function () use ($merchant) {
            $merchant->update(['is_blocked' => false]);
            return $merchant->makeVisible(['is_blocked']);
        });
    }
}
