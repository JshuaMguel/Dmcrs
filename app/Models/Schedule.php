<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'department_id',
        'semester',
        'subject_code',
        'subject_title',
        'section',
        'day_of_week',
        'time_start',
        'time_end',
        'room',
        'instructor_id',
        'instructor_name',
        'status',
        'type',
        'lecture_type',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
