<?php

namespace App\Models;

use App\Models\location\City;
use App\Models\location\Country;
use App\Models\location\Province;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonalData extends Pivot
{
    


    public function city()
    {
        return $this->belongsTo(City::class, 'users_id');
    }
    public function province()
    {
        return $this->hasOneThrough(Province::class, City::class, 'id', 'id', 'city_id', 'province_id');
    }

    public function country()
    {
        return $this->hasOneThrough(Country::class, Province::class, 'id', 'id', 'province_id', 'country_id');
    }

}
