<?php

namespace App\Http\Controllers\auth;

use App\Models\auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteGoogleContoller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callback(){
        $providers = Socialite::driver('google')->user();
        
            $user = User::updateOrCreate([
                'providers_id' => $providers->id,
            ], [
                'name' => $providers->name,
                'email' => $providers->email,
                'providers_tokens' => $providers->token,
                'providers_refresh_tokens' => $providers->refreshToken,
            ]);
         
            Auth::login($user);
         
            return redirect('/dashboard');
    }
}
