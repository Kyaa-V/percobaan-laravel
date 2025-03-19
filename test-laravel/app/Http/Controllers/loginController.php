<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Resources\UserResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class loginController extends Controller
{
    public function login (Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);


        $user = User::where('email',$request->email)->first();


        if($user->banned_until && Carbon::now()->lessThan($user->banned_until)){
            return response()->json([
                "message" => "Akun anda masih dibanned, Silahkan coba lagi nanti"
            ]);
        }

        $key = 'login-attempts:' . $request->ip();

        if(RateLimiter::tooManyAttempts($key,5)){
            if($user){
                $user->update(['banned_until'=> Carbon::now()->addMinute(30)]);
            }
            return response()->json([
                'message'=> 'Terlalu banyak request Akun dibanned selama 30 menit'
            ],429);
        }
        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            RateLimiter::hit($key,1800);
            return response()->json([
                "message"=>"Email atau Password Salah"
            ],400);
        }

        $token = $user->createToken('password incoret')->plainTextToken;
        RateLimiter::clear($key);

        if($user->role->role_name === "ADMIN"){
            return response()->json(["data"=>[
                "user"=>new UserResource($user),
                "message"=> "Selamat Datang Admin ku Sayang",
                "token"=>$token
            ]],200);
        }
        return response()->json(["data"=>[
            "user"=>new UserResource($user),
            "token"=>$token
        ]],200);
    }
    public function signin(Request $request)
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

}
