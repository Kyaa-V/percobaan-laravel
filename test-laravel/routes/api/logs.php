<?php

use App\Http\Middleware\RoleUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogsController;

Route::middleware(['auth:sanctum', RoleUser::class])->group(function () {
    Route::get('/get-logs', [LogsController::class, 'getLogs']);
});