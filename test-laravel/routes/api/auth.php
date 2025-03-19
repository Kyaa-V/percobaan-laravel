<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\loginController;

Route::post('/login', [loginController::class,"login"]);
Route::post('/signIn', [LoginController::class,"signin"]);