<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\loginController;
use App\Http\Controllers\UserController;

Route::post('/login', [loginController::class,"login"]);
Route::post('/sigIn', [UserController::class,"sigin"]);
Route::get('/getUser', [UserController::class,"get"])->middleware("auth:sanctum");
Route::get('/updateUser/{id}', [UserController::class,"updateUser"])->middleware("auth:sanctum");
Route::get('/deleteUser/{id}', [UserController::class,"deleteUser"])->middleware("auth:sanctum");
Route::get('/getUserById/{id}', [UserController::class,"getById"])->middleware("auth:sanctum");
