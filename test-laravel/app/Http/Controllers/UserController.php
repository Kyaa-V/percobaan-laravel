<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController
{
    /**
     * Display a listing of the resource.
     */
    public function get()
    {
        $user = User::all();
        return $user->toJson();
    }
    
    public function getById($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(["message"=> "user tidak ditemukan"],404);
        }
        return $user->toJson();
    }
    
    public function sigin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name'=> 'required',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        $token = $user->createToken('auth-token')->plainTextToken;
        
        return response()->json(["data"=>[
            "user"=>new UserResource($user),
            "token"=>$token
        ]],201);
    }

    /**
     * Store a newly created resource in storage.
     */
}
