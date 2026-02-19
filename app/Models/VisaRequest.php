<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisaRequest extends Model
{
    /** @use HasFactory<\Database\Factories\VisaRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'visa_id',
        'status',
        'notes',
    ];

    protected $appends = [
        'visa_name',
        'user_name',
    ];

    protected $hidden = [
        'visa',
        'user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visa()
    {
        return $this->belongsTo(Visa::class);
    }

    public function fields()
    {
        return $this->hasMany(VisaRequestField::class);
    }

    //!Accessors

    public function getVisaNameAttribute()
    {
        return $this->visa?->name;
    }

    public function getUserNameAttribute()
    {
        return $this->user?->name;
    }

    public function getShopNameAttribute()
    {
        return $this->user->shop?->name;
    }
}