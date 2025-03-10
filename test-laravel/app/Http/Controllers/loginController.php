<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class loginController extends Controller
{
    public function login (Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email',$request->email)->first();
        if(! $user || ! Hash::check($request->password,$user->password)){
            throw ValidationException::withMessages([
                'email' => ['Email atau Password salah'],
            ]);
        }
        $token = $user->createToken('password incoret')->plainTextToken;
        return response()->json([
            "token" => $token
        ]);
    }
}
