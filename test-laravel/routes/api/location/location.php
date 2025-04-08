<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\location\CityController;
use App\Http\Controllers\location\CountryController;
use App\Http\Controllers\location\ProvinceController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/get-country',[CountryController::class, 'getCountry']);
    Route::get('/get-state',[ProvinceController::class, 'getProvince']);
    Route::get('/get-state/{id}',[ProvinceController::class, 'getProvinceByCountryId']);
    Route::get('/get-city/{idCountry}/{idProvince}',[CityController::class, 'getCityByProvinceId']);
});
Route::get('/get-city',[CityController::class, 'getCity']);