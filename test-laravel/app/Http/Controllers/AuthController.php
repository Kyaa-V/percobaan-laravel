<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValcidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Akun tidak di temukan'
            ]);
        }

        if ($user->banned_until && Carbon::now()->lessThan($user->banned_until)) {
            return response()->json([
                "message" => "Akun anda masih dibanned, Silahkan coba lagi nanti"
            ]);
        }

        $key = 'login-attempts:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            if ($user) {
                $user->update(['banned_until' => Carbon::now()->addMinute(30)]);
            }
            return response()->json([
                'message' => 'Terlalu banyak request Akun dibanned selama 30 menit'
            ], 429);
        }
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            RateLimiter::hit($key, 1800);
            return response()->json([
                "message" => "Email atau Password Salah"
            ], 400);
        }

        $token = $user->createToken('password incoret')->plainTextToken;
        RateLimiter::clear($key);

        Cookie::make('user_token', $token, 18000, '/', null, true, true);

        if ($user->role->role_name === "ADMIN") {
            return response()->json(["data" => [
                "message" => "Selamat Datang Admin ku Sayang",
                "token" => $token,
                "user" => new UserResource($user)
            ]], 200);
        }
        return response()->json(["data" => [
            "user" => new UserResource($user),
            "token" => $token
        ]], 200);
    }
    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        $token = $user->createToken('auth-token')->plainTextToken;

        Cookie::make('user_token', $token, 18000, '/', null, true, true);

        return response()->json(["data" => new UserResource($user, "Registrasi Berhasil Silahkan Cek Email Untuk Melakukan Verifikasi.", 201, $token)], 201);
    }

    public function resendVerificationEmail(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email sudah diverifikasi.'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Email verifikasi telah dikirim ulang.']);
    }

    public function verifyEmail(Request $request, $id, $hash): RedirectResponse|JsonResponse
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Link verifikasi tidak valid.'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email sudah diverifikasi.', 'verified' => true], 400);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email berhasil diverifikasi!']);
    }
}
