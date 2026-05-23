<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Notifications\MerchantBlockStatusNotification;
use App\Notifications\UserLevelChangeNotification;
use App\Traits\HasFiles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Notification;
use Spatie\Permission\Models\Role;

/**
 * Class MerchantService.
 */
class MerchantService
{
    use HasFiles;

    public function show(string $id)
    {

        $merchant = User::with(['shop'])
            ->role('merchant', 'api')
            ->findOrFail($id)
            ->makeVisible(['is_blocked'])
            ->append(['verification_code', 'shop_status', 'is_blocked_text']);
        if ($merchant->shop) {
            $merchant->shop->append(['logo_full_path', 'identity_front_face_full_path', 'identity_back_face_full_path']);
        }
        return $merchant;
    }

    public function store(FormRequest $request)
    {
        $data = $request->validated();
        $data['email_verified_at'] = now();
        if (key_exists('email', $data) && $data['email'] == null)
            $data['email'] = '';
        if (key_exists('phone_number', $data) && $data['phone_number'] == null)
            $data['phone_number'] = '';

        return DB::transaction(function () use ($data) {

            $user = User::create($data);
            $user->assignRole(Role::where('name', 'merchant')->where('guard_name', 'api')->first());
            $user->wallet()->create([
                'available_balance' => (Float) 0,
                'marginal_balance' => (Float) 0,
                'total_balance' => (Float) 0,
            ]);
            return $user->load('wallet');
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
            Notification::send($merchant, new MerchantBlockStatusNotification($merchant, 'blocked'));
            return $merchant->makeVisible(['is_blocked'])->append(['is_blocked_text']);
        });
    }

    public function unblockMerchant(string $id)
    {
        $merchant = User::role('merchant', 'api')->findOrFail($id);
        return DB::transaction(function () use ($merchant) {
            $merchant->update(['is_blocked' => false]);
            Notification::send($merchant, new MerchantBlockStatusNotification($merchant, 'unblocked'));
            return $merchant->makeVisible(['is_blocked'])->append(['is_blocked_text']);
        });
    }

    public function changeMerchantLevel(string $id, string $decision)
    {
        $merchant = User::role('merchant', 'api')->findOrFail($id)
            ->makeVisible(['is_blocked'])
            ->append(['verification_code', 'shop_status', 'is_blocked_text']);

        return DB::transaction(function () use ($merchant, $decision) {
            $decision = strtolower($decision);
            in_array($decision, ['increase', 'decrease']) ?: throw new \InvalidArgumentException('Invalid decision. Use "increase" or "decrease".');

            if ($merchant->level >= 7 && $decision === 'increase') {
                throw new \InvalidArgumentException('The user is already at the maximum level.');
            }
            if ($merchant->level <= 1 && $decision === 'decrease') {
                throw new \InvalidArgumentException('The user is already at the minimum level.');
            }

            if ($decision === 'increase') {
                $x = $merchant->level + 1;
                Notification::send($merchant, new UserLevelChangeNotification($merchant, 'increase'));
            } else {
                $x = $merchant->level - 1;
                Notification::send($merchant, new UserLevelChangeNotification($merchant, 'decrease'));
            }

            $merchant->update(['level' => $x]);
            return $merchant;
        });
    }

    public function toggleVisaEligibility(string $id)
    {
        $merchant = User::role('merchant', 'api')->findOrFail($id);

        return DB::transaction(function () use ($merchant) {
            if ($merchant->can_apply_visa) {
                $merchant->update(['can_apply_visa' => 0]);
            } else {
                $merchant->update(['can_apply_visa' => 1]);
            }

            return $merchant;
        });
    }

    public function toggleTicketEligibility(string $id)
    {
        $merchant = User::role('merchant', 'api')->findOrFail($id);
        return DB::transaction(function () use ($merchant) {
            if ($merchant->can_hold_ticket) {
                $merchant->update(['can_hold_ticket' => 0]);
            } else {
                $merchant->update(['can_hold_ticket' => 1]);
            }

            return $merchant;
        });
    }
}