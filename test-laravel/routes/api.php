<?php

use Illuminate\Support\Facades\Route;


Route::put('/', function(){
    return response()->json(['message' => 'selamat datang di halaman utama kami']);
});

require __DIR__ . '/api/auth.php';
require __DIR__ . '/api/users.php';