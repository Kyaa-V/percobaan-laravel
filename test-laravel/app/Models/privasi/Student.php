<?php

namespace App\Models\privasi;

use App\Models\auth\User;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    


    public function user(){
       return $this->hasOne(User::class, 'users_id');
    }
    public function pregister(){
       return $this->hasOne(Pregister_schools::class, 'pregister_id');
    }
}
