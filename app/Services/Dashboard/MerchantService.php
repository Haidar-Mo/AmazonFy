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
}
