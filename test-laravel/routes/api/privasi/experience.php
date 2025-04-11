<?php

use App\Http\Controllers\privasi\ExpController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/postExp', [ExpController::class, 'postExperience']);
    Route::get('/getExpByAuthorId/{id}', [ExpController::class, 'getExperience']);
    Route::patch('/editExpById/{id}', [ExpController::class, 'editExpById']);
    Route::delete('/deleteExpById/{id}', [ExpController::class, 'deleteExpById']);
});
