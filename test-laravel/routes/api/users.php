<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\RoleUser;

Route::get('/getUser', [UserController::class, "get"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/updateUser/{id}', [UserController::class, "updateUser"]);
    Route::delete('/deleteUser/{id}', [UserController::class, "deleteUser"]);
    Route::get('/getUserById/{id}', [UserController::class, "getById"]);

    Route::middleware([RoleUser::class])->group(function () {
        Route::delete('/deleteUserByAdmin/{id}', [UserController::class, "deleteUser"]);
        Route::get('/getUserByIdByAdmin/{id}', [UserController::class, "getById"]);
    });
});