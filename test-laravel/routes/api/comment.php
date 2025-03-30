<?php

use App\Http\Controllers\CommentController;
use App\Http\Middleware\RoleUser;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/comment',[CommentController::class, 'getCommentsByAuthor']);
    Route::post('/comment',[CommentController::class, 'postComment']);
    Route::put('/comment/{id}',[CommentController::class, 'editCommentById']);

    Route::middleware([RoleUser::class])->group(function(){
        Route::delete('/comment/{id}',[CommentController::class, 'deleteCommentById']);
    });
});