<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// protected endpoints
Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('/v1/flights', \App\Http\Controllers\v1\FlightController::class);
    Route::apiResource('/v1/airports', \App\Http\Controllers\v1\AirportController::class);
    Route::apiResource('/v1/costumers', \App\Http\Controllers\v1\CostumerController::class);
    Route::get('/user', App\Http\Controllers\UserController::class.'@getUser');
    Route::get('/token', App\Http\Controllers\UserController::class.'@getAccesToken');
    Route::get('/role', App\Http\Controllers\UserController::class.'@checkRole');
});

Route::get('login', \App\Http\Controllers\UserController::class.'@AuthError')->name('login');