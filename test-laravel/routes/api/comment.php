<?php

use App\Http\Controllers\CommentController;
use App\Http\Middleware\RoleUser;
use Illuminate\Support\Facades\Route;


Route::get('/comment/{id}',[CommentController::class, 'getCommentsByAuthor']);
Route::post('/comment',[CommentController::class, 'postComment']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('/comment/{id}',[CommentController::class, 'editCommentById']);

    Route::middleware([RoleUser::class])->group(function(){
        Route::delete('/comment/{id}',[CommentController::class, 'deleteCommentById']);
    });
});