<?php

namespace App\Models\location;

use App\Models\location\City;
use App\Models\location\Country;
use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    

    public function country(){
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function city(){
        return $this->hasMany(City::class, 'state_id');
    }
}
