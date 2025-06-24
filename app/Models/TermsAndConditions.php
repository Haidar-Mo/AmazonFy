<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class TermsAndConditions extends Model
{
    use Translatable;
    public $translatedAttributes = ['title', 'details'];

}
