<?php

namespace App\Http\Controllers\auth;

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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan'
                ], 404);
            }

            if ($user->banned_until && Carbon::now()->lessThan($user->banned_until)) {
                return response()->json([
                    "success" => false,
                    "message" => "Akun anda masih dibanned, Silahkan coba lagi nanti"
                ], 403);
            }

            $key = 'login-attempts:' . $request->ip();

            if (RateLimiter::tooManyAttempts($key, 5)) {
                if ($user) {
                    $user->update(['banned_until' => Carbon::now()->addMinute(30)]);
                }
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak request. Akun dibanned selama 30 menit'
                ], 429);
            }

            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                RateLimiter::hit($key, 1800);
                return response()->json([
                    "success" => false,
                    "message" => "Email atau Password Salah"
                ], 400);
            }

            $user->tokens()->delete();
            $token = $user->createToken('auth_token', [
                $user->role->role_name === 'ADMIN' ? ['admin'] : ['user']
            ])->plainTextToken;

            RateLimiter::clear($key);

            Cookie::queue('user_token', $token, 18000, '/', null, true, true);

            $responseData = [
                "success" => true,
                "data" => [
                    "user" => new UserResource($user),
                    "token" => $token
                ]
            ];

            if ($user->role->role_name === "ADMIN") {
                $responseData['data']['message'] = "Selamat Datang Admin ku Sayang";
            }

            return response()->json($responseData, 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat login'
            ], 500);
        }
    }

    public function signin(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email|unique:users,email',
                'name' => 'required|min:4|max:16',
                'password' => 'required|min:7|max:20',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            //event(new Registered($user));

            $token = $user->createToken('auth-token')->plainTextToken;

            Cookie::queue('user_token', $token, 18000, '/', null, true, true);

            return response()->json([
                'success' => true,
                'data' => new UserResource(
                    $user,
                    "Registrasi Berhasil Silahkan Cek Email Untuk Melakukan Verifikasi.",
                    200,
                    $token
                ),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi registrasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'email' => $request->email ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat registrasi'
            ], 500);
        }
    }

    public function resendVerificationEmail(Request $request): JsonResponse
    {
        try {
            if ($request->user()->hasVerifiedEmail()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email sudah diverifikasi.'
                ], 400);
            }

            $request->user()->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'Email verifikasi telah dikirim ulang.'
            ]);
        } catch (\Exception $e) {
            Log::error('Resend verification error: ' . $e->getMessage(), [
                'user_id' => $request->user()->id ?? null
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim ulang email verifikasi'
            ], 500);
        }
    }

    public function verifyEmail(Request $request, $id, $hash): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Link verifikasi tidak valid.'
                ], 400);
            }

            if ($user->hasVerifiedEmail()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email sudah diverifikasi.',
                    'verified' => true
                ], 400);
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil diverifikasi!'
            ]);
        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage(), [
                'user_id' => $id,
                'hash' => $hash
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat verifikasi email'
            ], 500);
        }
    }
}
