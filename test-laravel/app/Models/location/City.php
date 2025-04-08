<?php

namespace App\Models\location;

use App\Models\location\States;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    

    public function country(){
        return $this->belongsTo(Country::class, 'country_id');
    }
    public function state(){
        return $this->belongsTo(States::class, 'state_id');
    }
}
