<?php

use App\Http\Middleware\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/deleteUser/{id}', [UserController::class, "deleteUser"]);
    Route::get('/getUserById/{id}', [UserController::class, "getById"]);
    Route::patch('/updatePasswordUser/{id}', [UserController::class, "updatePaswordUser"]);
    
    Route::middleware([RoleUser::class])->group(function () {
        Route::delete('/deleteUserByAdmin/{id}', [UserController::class, "deleteUser"]);
        Route::get('/getUserByIdByAdmin/{id}', [UserController::class, "getById"]);
    });
});
Route::get('/getUser', [UserController::class, "get"]);