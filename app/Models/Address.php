<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;


    public $fillable = [
        'network_name',
        'target',
        'qr_image'
    ];

    protected $appends = [
        'qr_image_full_path'
    ];
    
    //! Accessories

    public function getQrImageFullPathAttribute()
    {
        return asset($this->qr_image);
    }

}
