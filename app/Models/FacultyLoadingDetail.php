<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacultyLoadingDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'faculty_loading_header_id',
        'instructor_id',
        'subject_code',
        'section',
        'day_of_week',
        'time_start',
        'time_end',
        'room',
        'units',
    ];

    protected $casts = [
        'time_start' => 'datetime:H:i',
        'time_end' => 'datetime:H:i',
        'units' => 'decimal:2',
    ];

    /**
     * Get the faculty loading header.
     */
    public function header()
    {
        return $this->belongsTo(FacultyLoadingHeader::class, 'faculty_loading_header_id');
    }

    /**
     * Get the instructor (user).
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Get the schedules linked to this faculty loading detail.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'faculty_loading_detail_id');
    }

    /**
     * Scope to filter by instructor.
     */
    public function scopeByInstructor($query, $instructorId)
    {
        return $query->where('instructor_id', $instructorId);
    }

    /**
     * Scope to filter by header.
     */
    public function scopeByHeader($query, $headerId)
    {
        return $query->where('faculty_loading_header_id', $headerId);
    }
}


