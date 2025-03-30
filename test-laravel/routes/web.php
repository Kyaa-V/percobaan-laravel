<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialiteGoogleContoller;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/auth/redirect', [SocialiteGoogleContoller::class, 'redirect']);
Route::get('/auth/callback', [SocialiteGoogleContoller::class, 'callback']);
