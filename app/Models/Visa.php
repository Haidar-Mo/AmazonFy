<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    /** @use HasFactory<\Database\Factories\VisaFactory> */
    use HasFactory, Translatable;


    protected $fillable = [
        'duration',
        'price',
    ];

    public $translatedAttributes = ['name', 'description','destination'];


    public function requiredFields()
    {
        return $this->hasMany(VisaRequiredField::class);
    }

    public function visaRequests()
    {
        return $this->hasMany(VisaRequest::class);
    }


}

