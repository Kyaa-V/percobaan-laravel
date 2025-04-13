<?php

namespace App\Models\privasi;

use App\Models\auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Experience extends Model
{
    use HasUuids, HasFactory;   

    public $incrementing = false;
    protected $keyType = 'string'; 

    protected $fillable = [
        'id',
        'position',
        'company',
        'location',
        'status',
        'your_skills',
        'start_date',
        'end_date',
        'users_id'
    ];

    public function user(){
        return $this->belongsTo(User::class,'users_id');
    }
}
