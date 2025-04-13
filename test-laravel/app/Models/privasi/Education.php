<?php

namespace App\Models\privasi;

use App\Models\auth\User;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $fillable = [
        'graduation_years','name_schools','schools',
        'major','average_value','type_schools',
        'diploma_date','major','users_id'
    ];


    public function users(){
        return $this->belongsTo(User::class, 'users_id');
    }
}
