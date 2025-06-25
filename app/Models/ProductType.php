<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['name'];

    //protected $fillable =['name'];

    public function products()
    {
        return $this->hasMany(Product::class, 'type_id');
    }
}
