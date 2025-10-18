<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'profile_image',
        'bio',
        'contact_number',
        'is_active',
    ];
    /**
     * Get the department for the user.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Check if user is an Academic Head
     */
    public function isAcademicHead(): bool
    {
        return $this->role === 'academic_head';
    }

    /**
     * Check if user is a Department Chair
     */
    public function isDepartmentChair(): bool
    {
        return $this->role === 'department_chair';
    }

    /**
     * Check if user is a Faculty member
     */
    public function isFaculty(): bool
    {
        return $this->role === 'faculty';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
