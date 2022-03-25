<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\RestController;
use App\Services\v1\AirportService;

class AirportController extends RestController
{
    public $pkey = 'iataCode';
    public function __construct(AirportService $service)
    {
        parent::__construct($service);
    }
}
