<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Airport extends RestModel
{    
    use HasFactory;
    public $timestamps = false;
    protected $table = 'airports';
    
    public function commingFlights()
    {
        return $this->hasMany(Flight::class,'arrivalAirport_id');
    }

    public function departingFlight()
    {
        return $this->hasMany(Flight::class,'departureAirport_id');
    }
}
