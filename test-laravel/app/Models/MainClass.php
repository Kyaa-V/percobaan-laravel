<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainClass extends Model
{
    public function class(){
        return $this->belongsTo(Classes::class, 'classes_id');
    }

}
    