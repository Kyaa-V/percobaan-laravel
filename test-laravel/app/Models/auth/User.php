<?php

namespace App\Models\auth;

use App\Models\Comment;
use App\Models\auth\Role;
use App\Models\privasi\Education;
use Laravel\Sanctum\HasApiTokens;
use App\Models\privasi\Experience;
use App\Models\privasi\Pregister_schools;
use App\Models\privasi\Student;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasUuids;


    public $incrementing = false;
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<stfor (let i = 0; i < 5; i++) {
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'role_name',
        'name_city',
        'city_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function pregister()
    {
        return $this->belongsTo(Pregister_schools::class, 'users_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'users_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'author_id');
    }
    public function education()
    {
        return $this->hasMany(Education::class, 'users_id');
    }
    public function authorComments()
    {
        return $this->hasMany(Comment::class, 'users_id');
    }
    public function experience()
    {
        return $this->hasMany(Experience::class, 'users_id');
    }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
