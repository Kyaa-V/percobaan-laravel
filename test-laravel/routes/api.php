<?php

use Illuminate\Support\Facades\Route;


Route::put('/', function(){
    return response()->json(['message' => 'selamat datang di halaman utama kami']);
});

require __DIR__ . '/api/auth/auth.php';
require __DIR__ . '/api/auth/users.php';
require __DIR__ . '/api/comment.php';
require __DIR__ . '/api/logs.php';
require __DIR__ . '/api/personal-data.php';
require __DIR__ . '/api/experience.php';
require __DIR__ . '/api/location/location.php';