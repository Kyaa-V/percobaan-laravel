<?php

namespace App\Models;

use App\Models\privasi\Student;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    

    public function student(){
        return $this->hasMany(Student::class, 'class_id');
    }
    public function mainClass(){
        return $this->hasMany(MainClass::class, 'classes_id');
    }
}
