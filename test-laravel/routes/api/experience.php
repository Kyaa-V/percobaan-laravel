<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\expController;
use App\Http\Middleware\RoleUser;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/postExp', [expController::class, 'postExperience']);
    Route::get('/getExpByAuthorId/{id}', [expController::class, 'getExperience']);
    Route::patch('/editExpById/{id}', [expController::class, 'editExpById']);
    Route::delete('/deleteExpById/{id}', [expController::class, 'deleteExpById']);
});
