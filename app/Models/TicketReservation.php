<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReservation extends Model
{

    protected $fillable = [
        'user_id',
        'air_line_id',
        'status',
        'notes'
        /*   'origin',
          'destination',
          'departure_date',
          'return_date',
          'passengers_count',
          'cabin_class', //: economy / business / first
          'status', */
    ];

    protected $appends = [
        'ticket_name',
        'user_name',
    ];

    protected $hidden = [
        'air_line',
        'user',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function airLine()
    {
        return $this->belongsTo(AirLine::class);
    }

    public function fields()
    {
        return $this->hasMany(TicketReservationField::class);
    }

    //! Accessors

    public function getTicketNameAttribute()
    {
        return $this->airLine?->name;
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
