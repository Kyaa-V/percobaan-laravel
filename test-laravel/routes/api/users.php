<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleUser;

Route::middleware('auth:sanctum')->group(function(){
    Route::put('/updateUser/{id}', [UserController::class,"updateUser"]);
    Route::get('/deleteUser/{id}', [UserController::class,"deleteUser"]);
    Route::get('/getUserById/{id}', [UserController::class,"getById"]);
});
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/getUser', [UserController::class,"get"])->middleware([RoleUser::class]);
});
