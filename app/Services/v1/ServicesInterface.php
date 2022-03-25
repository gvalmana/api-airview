<?php
namespace App\Services\v1;

interface ServicesInterface{

    public function create($request);
    public function update($request, $paramaters);
    public static function filter($data, $keys =[]);
}