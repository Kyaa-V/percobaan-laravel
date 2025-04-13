<?php

namespace App\Models\privasi;

use App\Models\auth\User;
use Illuminate\Database\Eloquent\Model;

class Pregister_schools extends Model
{
    
    protected $fillable = [
        'name','emails','schools',
        'photo','SKL','KTP',
        'AKTA_KELAHIRAN','RAPORT','NISN',
        'PRESTASI','major','NPSN', 'users_id'
    ];


    public function user(){
       return $this->hasOne(User::class, 'users_id');
    }
    public function student(){
       return $this->belongsTo(Student::class, 'pregister_id');
    }
}
