<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    protected $fillable = [
        'department_id',
        'subject_code',
        'subject_title',
        'description'
    ];

    /**
     * Get the department that owns the subject.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
