<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'flights';
    
    public function arrivalAirport()
    {
        return $this->belongsTo(Airport::class,'arrivalAirport_id');
    }

    public function departureAirport()
    {
        return $this->belongsTo(Airport::class,'departureAirport_id');
    }

    public function Pasajeros()
    {
        return $this->belongsToMany(Costumer::class,'flight_customer');
    }
}
