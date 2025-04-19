<?php

namespace App\Models;

use App\Models\privasi\Student;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
   
    public function student(){
        return $this->hasMany(Student::class, 'majors_id');
    }
}
