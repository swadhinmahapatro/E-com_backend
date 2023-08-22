<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    function register(Request $req)
    {

        $user = new User;
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->number = $req->input('number');
        $user->dob = $req->input('dob');
        $user->password = Hash::make($req->input('password'));
        try {
            $user->save();
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

        $user = User::where('email', $req->input('email'))->first();
        if (!$user) {
            return response()->json(['message' => 'Failed to login', 'reason' => 'User not found'], 500);
        }

        if (Hash::check($req->password, $user->password)) {
            return response()->json(['message' => 'Login successful'], 200);
        } else {
            return response()->json(['message' => 'Failed to login', 'reason' => 'Incorrect password'], 500);
        }
    }
}
