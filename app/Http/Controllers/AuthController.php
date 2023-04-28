<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //REGISTER
    public function signup(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users|email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']) //this will encrypt the password thru Hash
        ]);

        $token = $user->createToken('capstoneapi')->plainTextToken; //this will automatically log you in after registration

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201); //201 means success and something was created
    }

    //LOGOUT
    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return[
            'message' => "Logged out"
        ];
    }

    //LOG IN
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        
        //CHECK EMAIL
        $user = User::where('email', $fields['email'])->first();//first instance of the unique object
        
        // Alberto Overide
        if (!$user) {
            return response([
                'message' => 'Incorrect email or password'
            ], 401);
        }

        //CHECK PASSWORD
        if (!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'message' => 'Incorrect Credentials'
            ], 401);
        }

        $token = $user->createToken('capstoneapi')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200); //200 means success
    }
}
