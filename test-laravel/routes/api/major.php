<?php

use App\Models\Classes;
use App\Models\Major;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/post-major', Major::class, 'postMajor');
    Route::post('/post-class', Classes::class, 'postClass');
});