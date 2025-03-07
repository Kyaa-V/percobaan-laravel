<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;

Route::post('/login', [loginController::class,"login"])->middleware('auth:sanctum');
