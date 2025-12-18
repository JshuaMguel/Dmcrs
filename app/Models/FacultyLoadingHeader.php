<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacultyLoadingHeader extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'semester',
        'school_year',
        'uploaded_by',
        'status',
        'remarks',
    ];

    /**
     * Get the department that owns the faculty loading header.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user who uploaded the faculty loading.
     */
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the faculty loading details.
     */
    public function details()
    {
        return $this->hasMany(FacultyLoadingDetail::class, 'faculty_loading_header_id');
    }

    /**
     * Get the FLH code (auto-generated format: FLH-00001).
     */
    public function getFlhCodeAttribute()
    {
        return 'FLH-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Scope to filter by department.
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter active faculty loadings.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}


