<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\privasi\PersonalDataController;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/post-personal-data',[PersonalDataController::class,'postDataPersonal']);
    Route::patch('/edit-personal-data/{id}',[PersonalDataController::class,'editDataPersonalById']);
    Route::get('/get-personal-data/{id}', [PersonalDataController::class, 'getDataPersonalById' ]);
});