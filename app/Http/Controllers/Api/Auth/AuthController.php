<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login Method
     */

    public function login(LoginRequest $request){
//        $token = auth()->attempt($request->only('email', 'password'));
        $token = auth()->attempt($request->validated());
        if ($token){
            return $this->responseWithToken($token, auth()->user());
        }else{
            return response()->json(
                [
                    'status' => 'failed',
                    'message' => 'Invalid credentials'
                ], 401);
        }
    }

    /**
     * Registration Method
     */

    public function register(RegistrationRequest $request){
//        $user = User::create($request->validated());
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        if ($user){
            $token = auth()->login($user);
            return $this->responseWithToken($token, $user);
        }else{
            return response()->json([
                'status' => 'faild',
                'message' => 'An error occured while creating user'

            ], 500);
        }
    }

    /**
     *
     * Return JWT acces Method
     */

    public function responseWithToken($token, $user){
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    /**
     * Logout Method
     */
    public function logout(Request $request){

    }
}
