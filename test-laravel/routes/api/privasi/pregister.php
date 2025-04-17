<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PregisterSchoolsController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/pregister', [PregisterSchoolsController::class, 'postPregister']);
    Route::get('/get-pregister', [PregisterSchoolsController::class, 'getDataPregister']);
    Route::get('/get-pregister-by-id/{id}', [PregisterSchoolsController::class, 'getDataPregisterById']);
});