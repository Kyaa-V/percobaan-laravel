<?php

namespace App\Models\privasi;

use App\Models\auth\User;
use App\Models\Classes;
use App\Models\Major;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    


    public function user(){
       return $this->hasOne(User::class, 'users_id');
    }
    public function pregister(){
       return $this->hasOne(Pregister_schools::class, 'pregister_id');
    }
    public function major(){
      return $this->belongsTo(Major::class, 'majors_id');
    }
    public function class(){
      return $this->belongsTo(Classes::class, 'class_id');
    }
}
