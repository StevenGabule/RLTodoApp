<?php

namespace App\Http\Controllers\api\v1\Users;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Authentication extends Controller
{
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($request->only('email', 'password'))){
            $userToken = Auth::user()->createToken('Authentication')->accessToken;
            return response(['user' => Auth::user(), 'access_token' => $userToken], 200);
        }

        return response('Invalid credentials', 401);
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::user()->token()->revoke();
            return response('Logged out', 200);
        }

        return response('User isnt logged in', 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);

        $newUser = new User();
        $newUser->name = $request->input('name');
        $newUser->email = $request->input('email');
        $newUser->password = Hash::make($request->input('password'));
        $newUser->save();
        if ($newUser->id) {
            return response(['user' => $newUser], 201);
        }
        return response('Unable to create new user', 500);
    }
}
