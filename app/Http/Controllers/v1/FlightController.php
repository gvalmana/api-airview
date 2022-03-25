<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\RestController;
use App\Services\v1\FlightService;

class FlightController extends RestController
{

    public $pkey = 'flightNumber';
    public function __construct(FlightService $service)
    {
        parent::__construct($service);
    }
}
