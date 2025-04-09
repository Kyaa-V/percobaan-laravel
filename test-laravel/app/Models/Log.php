<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'channel',
        'level',
        'message',
        'context'
    ];

    protected $casts = [
        'context' => 'array',
    ];
}
