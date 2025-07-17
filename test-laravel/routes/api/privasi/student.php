<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\privasi\StudentController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/post-student', StudentController::class, 'postStudent');
});