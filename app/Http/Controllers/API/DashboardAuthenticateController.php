<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardAuthenticateController extends Controller
{
    public function authenticate()
    {
       if (Auth::attempt(['email' => request('email'), 'password' => request('password')]) && Auth::user()->hasAnyRole(["viewer","admin"])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('appToken')->accessToken;
           //After successfull authentication, notice how I return json parameters
            return response()->json([
              'success' => true,
              'token' => $success['token'],
              'user' => [
                "id"=>$user->id,
                "name"=>$user->name,
                "email"=>$user->email,
                "roles"=>$user->roles->map(function($role){return $role->name;})

            ]
          ]);
        } else {
       //if authentication is unsuccessfull, notice how I return json parameters
          return response()->json([
            'success' => false,
            'message' => 'Invalid Email or Password',
        ], 401);
        }
    }
    
}
