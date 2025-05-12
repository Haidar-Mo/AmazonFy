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
use URL;

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
        'verify_code',
        'verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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


    public function shop()
    {
        return $this->hasOne(Shop::class);
    }
    public function chat()
    {
        return $this->hasMany(Chat::class);
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
        return $this->shop()->first() ? $this->shop()->first()->status : 'غير موثق';
    }
}
