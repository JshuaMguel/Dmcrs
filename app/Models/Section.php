<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'year_level',
        'section_name',
    ];

    /**
     * Get the department that owns the section.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the full section name (e.g., BSIT-1A, BAT-2B)
     */
    public function getFullNameAttribute()
    {
        $deptCode = $this->department ? $this->department->name : 'Unknown';
        return "{$deptCode}-{$this->year_level}{$this->section_name}";
    }

    /**
     * Get the abbreviated section name for dropdown display (e.g., BSIT-1A, BAT-2B)
     */
    public function getAbbreviatedNameAttribute()
    {
        if (!$this->department) {
            return "{$this->year_level}{$this->section_name}";
        }
        
        // Extract abbreviation from department name (e.g., "BSIT - Bachelor..." -> "BSIT")
        $deptName = $this->department->name;
        $abbreviation = strpos($deptName, ' - ') !== false 
            ? trim(substr($deptName, 0, strpos($deptName, ' - ')))
            : $deptName;
            
        return "{$abbreviation}-{$this->year_level}{$this->section_name}";
    }

    /**
     * Get makeup requests for this section.
     */
    public function makeupRequests()
    {
        return $this->hasMany(MakeUpClassRequest::class);
    }
}
