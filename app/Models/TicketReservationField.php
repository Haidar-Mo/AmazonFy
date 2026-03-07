<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketReservationField extends Model
{
    /** @use HasFactory<\Database\Factories\TicketReservationFieldFactory> */
    use HasFactory;

    protected $fillable = [
        'ticket_reservation_id',
        'air_line_required_field_id',
        'value',
    ];

    protected $appends = [
        'key',
        'is_file',

    ];

    protected $hidden = [
        'airLineRequiredField',
    ];

    public function ticketReservation()
    {
        return $this->belongsTo(TicketReservation::class);
    }

    public function airLineRequiredField()
    {
        return $this->belongsTo(AirLineRequiredField::class);
    }

    //! Accessors

    public function getKeyAttribute()
    {
        return $this->airLineRequiredField?->key;
    }

    public function getIsFileAttribute()
    {
        return $this->airLineRequiredField?->is_file;
    }
}
