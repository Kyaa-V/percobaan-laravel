<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    protected $fillable = [
        'id',
        'authors_id',
        'content',
        'users_id',
        'parent_id'
    ];

    public function author(){
        return $this->belongsTo(User::class,'authors_id');
    }

    public function users(){
        return $this->belongsTo(User::class,'users_id');
    }
}
