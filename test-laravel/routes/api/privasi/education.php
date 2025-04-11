<?php

use App\Http\Controllers\privasi\EducationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('post-education', [EducationController::class, 'postEducation']);
    Route::get('get-education-by-id/{id}', [EducationController::class, 'getEducationById']);
    Route::patch('/edit-education-by-id/{id}', [EducationController::class, 'editEducationById']);
    Route::delete('/delete-education-by-id/{id}', [EducationController::class, 'deleteEducationById']);
});