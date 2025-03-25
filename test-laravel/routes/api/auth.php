<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SocialiteGoogleContoller;

Route::get('/auth/redirect', [SocialiteGoogleContoller::class, "redirect"]);
Route::get('/auth/redirect', [SocialiteGoogleContoller::class, "callback"]);
Route::post('/login', [AuthController::class,"login"]);
Route::post('/signIn', [AuthController::class,"signin"]);
Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->middleware('auth:sanctum');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');