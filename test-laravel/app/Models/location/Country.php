<?php

namespace App\Models\location;

use App\Models\location\Province;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name_countries'];

    public function provinces()
    {
        return $this->hasMany(Province::class,'country_id');
    }
}
