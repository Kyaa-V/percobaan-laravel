<?php

namespace App\Models\location;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    


    public function state(){
        return $this->hasMany(City::class, 'country_id');
    }
    public function city(){
        return $this->hasMany(City::class, 'country_id');
    }
}
