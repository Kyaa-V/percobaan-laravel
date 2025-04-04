<?php

use App\Http\Controllers\CommentController;
use App\Http\Middleware\RoleUser;
use Illuminate\Support\Facades\Route;

Route::get('/getCommentByAuthor/{id}',[CommentController::class, 'getCommentsByAuthor']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/postComment',[CommentController::class, 'postComment']);
    Route::patch('/editCommentById/{id}',[CommentController::class, 'editCommentById']);
    
    Route::middleware([RoleUser::class])->group(function(){
        Route::delete('/deleteCommentById/{id}',[CommentController::class, 'deleteCommentById']);
    });
});