<?php

namespace App\Models;

use App\Models\auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory,HasUuids;

    protected $fillable = [
        'authors_id',
        'content',
        'users_id',
        'parent_id'
    ];

    public $incrementing = false;
    protected $keyType = 'string'; 

    public function author(){
        return $this->belongsTo(User::class,'authors_id');
    }

    public function users(){
        return $this->belongsTo(User::class,'users_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('replies');
    }
}
