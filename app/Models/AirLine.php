<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class AirLine extends Model
{
    use Translatable;

    protected $fillable = [
        'iata_code', //: (2-letter, unique:name-iataCode)
        'is_active',
    ];

    public $translatedAttributes = [
        'name'
    ];

    public function requiredFields()
    {
        return $this->hasMany(AirLineRequiredField::class);
    }

    public function ticketReservation()
    {
        return $this->hasMany(TicketReservation::class);
    }



}
