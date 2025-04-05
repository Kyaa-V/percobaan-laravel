<?php

namespace App\Models\location;

use App\Models\location\Province;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name_city'];

    public function province(){
        return $this->hasOne(Province::class, 'province_id');
    }
}
