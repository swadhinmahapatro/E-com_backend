<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\Welcomemail;
use Validator;

class UserController extends Controller
{
    function register(Request $req)
    {
        $validation = $req->validate([
            'name' => 'required',
            'email' => 'required|email',
            'number' => 'required',
            'dob' => 'required',
            'password' => 'required'
        ]);

        $user = new User;
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->number = $req->input('number');
        $user->dob = $req->input('dob');
        $user->admin = false;
        $user->password = Hash::make($req->input('password'));

        try {
            $user->save();
            Mail::to($user->email)->send(new Welcomemail($user));
            return response()->json(['message' => 'User registered successfully'], 200);
        } catch (QueryException $e) {
            if ($e->getCode() == '23000') {
                return response()->json(['message' => 'Failed to register user. Email or phone number already taken.'], 500);
            }
            return response()->json(['message' => 'An error occurred while registering user'], 500);
        }
    }
    function login(Request $req)
    {
        try {
            $input = $req->all();
            $validation = validator($input, [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validation->fails()) {
                return response()->json(['message' => $validation->errors()], 422);
            }

            if (Auth::attempt(['email' => $req->input('email'), 'password' => $req->input('password')])) {
                $user = Auth::user();
                $token = $user->createToken('Token Name')->accessToken;
                $refreshToken = $user->createToken('Refresh Token')->accessToken;
                return response()->json(['message' => 'Login successful', 'token' => $token, 'refresh_token' => $refreshToken, 'user' => $user], 200);
            } else {
                return response()->json(['message' => 'Failed to login', 'reason' => 'Incorrect password'], 500);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to login'], 500);}
    }
    function userDetails()
    {
        $user = Auth::guard('api')->user();
        return response()->json(['user' => $user], 200);
    }
    function getUserById($id)
    {
        try {
            if (Auth::guard('api')) {
                $user = User::find($id);
                return response()->json(['user' => $user], 200);
            }
        } catch (QueryException $e) {
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    function updateUserDetailsById($id, Request $req)
    {
        if (Auth::guard('api')) {
            $user = User::find($id);

            if ($req->has('name')) {
                $user->name = $req->input('name');
            }
            if ($req->has('email')) {
                $user->email = $req->input('email');
            }
            if ($req->has('number')) {
                $user->number = $req->input('number');
            }
            if ($req->has('dob')) {
                $user->dob = $req->input('dob');
            }
            if ($req->has('password')) {
                $user->dob = Hash::make($req->input('password'));
            }
            try {
                $user->save();
                return response()->json(['user' => $user], 200);
            } catch (QueryException $e) {
                if ($e->getCode() == '23000') {
                    return response()->json(['message' => 'Failed to Update user. Email or phone number already taken.'], 500);
                }
            }
        }
    }
}
