<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable =[
        'role_name'
    ];
    public function user(){
      return  $this->hasMany(User::class,'role_id');
    }
}
