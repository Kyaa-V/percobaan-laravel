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

     public function updateUser(Request $request, $id){

        $request->validate([
            'password' => 'required|min:6',
        ]);

        $user = User::find($id);
        $user->update([
            "password" => Hash::make($request->password),
        ]);

        return response()->json([
            "user" => new UserResource($user)
        ]);
     }

    public function get()
    {
        $user = User::with('role')->get();
        return response()->json(["data"=>[
            "user"=> UserResource::collection($user)
        ]],201);
    }
    public function deleteUser($id)
    {
        $user = User::find(1);
        $user->delete();
        return response()->json(["messsagge"=> "Berhasil menghapus User"],201);
    }
    
    public function getById($id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json(["message"=> "user tidak ditemukan"],404);
        }
        return response()->json(["data"=>[
            "user"=>new UserResource($user),
        ]],201);
    }
    /**
     * Store a newly created resource in storage.
     */
}
