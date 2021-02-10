<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use JWT;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        $request->validate([
                    'email'=>'required',
                    'password'=>'required',                   
        ]);


        
        $input = $request->only(['email', 'password']);


            try {
                if (!$token = JWTAuth::attempt($input)) {
                    return response()->json(['error' => 'invalid_credentials'], 400);
                }
            } catch (JWTException $e) {
                return response()->json(['error' => 'could_not_create_token'], 500);
            }

            // return response()->json(compact('token'));

          $input['token'] = $token;
          return $input;
        // $input = $request->only(['email', 'password']);
        // $token = auth()->attempt($input);
        // $userToken = auth()->user()->getJWTIdentifier();
        // return $userToken;
    }

    
}
