<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasPermissions, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'is_blocked',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        // 'is_blocked',
        'remember_token',
    ];

    // protected $guard_name = 'api';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function shop(): HasOne
    {
        return $this->hasOne(Shop::class);
    }
    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function verificationCode(): HasOne
    {
        return $this->hasOne(Code::class);
    }


    //! Accessories

    public function getRoleNameAttribute()
    {
        return $this->getRoleNames()->first();
    }

    public function getPermissionsNamesAttribute()
    {
        return $this->getPermissionNames();
    }

    public function getShopStatusAttribute()
    {
        return $this->shop()->where('status', 'active')->first() ? __('texts.shop.status.verified') : __('texts.shop.status.unverified');
    }

    public function getIsBlockedTextAttribute()
    {
        return $this->is_blocked ? __('texts.user.status.blocked') : __('texts.user.status.unblocked');
    }

    public function getVerificationCodeAttribute()
    {
        return $this->verificationCode()->first()?->verification_code;
    }
}
