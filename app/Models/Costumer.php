<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Costumer extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'costumers';
    
    public function Vuelos()
    {
        return $this->belongsToMany(Flight::class, 'flight_customer');
    }
}
