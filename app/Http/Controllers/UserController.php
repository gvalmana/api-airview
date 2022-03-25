<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUser()
    {
        return response()->json(Auth::user());
    }

    public function getAccesToken()
    {
        return response()->json(json_decode(Auth::token()));
    }

    public function checkRole()
    {
        $res =[
            'administrador' => Auth::hasRole('api-airview', 'administrador'),
            'supervisor' => Auth::hasRole('api-airview', 'supervisor'),
            'operador' => Auth::hasRole('api-airview', 'operador'),
        ];
        
        return response()->json($res);
    }

    public function AuthError()
    {
        return response()->json(['message'=>'Error de autenticación'], 403);
    }
}
