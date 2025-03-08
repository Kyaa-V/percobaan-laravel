<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable =[
        'Role'
    ];
    public function user(){
        $this->hasMany(User::class);
    }
}
