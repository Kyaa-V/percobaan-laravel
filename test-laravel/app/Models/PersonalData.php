<?php

namespace App\Models;

use App\Models\location\City;
use App\Models\location\Country;
use App\Models\location\States;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PersonalData extends Pivot
{
    
    protected $table = 'personal_datas';


    public function city()
    {
        return $this->belongsTo(City::class, 'users_id');
    }
    public function province()
    {
        return $this->hasOneThrough(States::class, City::class, 'id', 'id', 'city_id', 'province_id');
    }

    public function country()
    {
        return $this->hasOneThrough(Country::class, States::class, 'id', 'id', 'province_id', 'country_id');
    }

}
