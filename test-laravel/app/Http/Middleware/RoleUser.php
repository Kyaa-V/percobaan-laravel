<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if(!$user){
            return response()->json([
                'message' => 'user tidak di temukan'
            ],401);
        }
        if($user->role->role_name !== "ADMIN"){
            return response()->json([
                'message'=>'Role anda tidak mencukupi untuk mengakses halaman ini'
            ],403);
        }
        return $next($request);
    }
}
