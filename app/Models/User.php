<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;

class User extends Authenticatable implements CanResetPasswordContract, MustVerifyEmail
{
    use HasFactory, Notifiable, CanResetPassword, MustVerifyEmailTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getAvatarAttribute($value)
    {
        if ($value) {
            return $value;
        }
        // Return a default avatar URL if no avatar is set
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function teacherClasses()
    {
        return $this->hasMany(TeacherClass::class);
    }

    public function classes()
    {
        return $this->belongsToMany(ClassRoom::class, 'teacher_class', 'teacher_id', 'class_id');
    }
}
