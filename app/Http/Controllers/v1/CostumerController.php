<?php

namespace App\Http\Controllers\v1;

use App\Costumer;
use App\Http\Controllers\RestController;
use App\Services\v1\CostumerService;

class CostumerController extends RestController
{
    public $pkey = 'passport';
    public function __construct(CostumerService $service)
    {
        parent::__construct($service);
    }
}
