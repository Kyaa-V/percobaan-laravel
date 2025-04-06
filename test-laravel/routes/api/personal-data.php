<?php

use App\Http\Controllers\PersonalDataController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/post-personal-data',[PersonalDataController::class,'postDataPersonal']);
    Route::get('/get-personal-data/{id}', [PersonalDataController::class, 'getDataPersonalById' ]);
});