<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the subjects for the department.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    /**
     * Get the sections for the department.
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    /**
     * Get the users for the department.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the students for the department.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Get the faculty loading headers for the department.
     */
    public function facultyLoadingHeaders()
    {
        return $this->hasMany(FacultyLoadingHeader::class);
    }
}
