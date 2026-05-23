<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaRequiredFieldTranslation extends Model
{

    protected $fillable = [
        'locale',
        'label'
    ];

    public $timestamps = false;

}
