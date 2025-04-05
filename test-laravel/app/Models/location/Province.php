<?php

namespace App\Models\location;

use App\Models\location\City;
use App\Models\location\Country;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $fillable = ['name_provinces'];

    public function country(){
        return $this->hasOne(Country::class,'country_id');
    }
    public function city(){
        return $this->hasMany(City::class,'province_id');
    }

}
