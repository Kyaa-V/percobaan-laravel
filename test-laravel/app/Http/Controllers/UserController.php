<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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

    /**
     * Store a newly created resource in storage.
     */
}
