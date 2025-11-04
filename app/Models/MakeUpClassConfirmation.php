<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakeUpClassConfirmation extends Model
{
    use HasFactory;

    protected $fillable = [
        'make_up_class_request_id',
        'student_id',
        'student_email', // Add email field for non-registered students
        'student_id_number', // Student ID like 2022305792
        'student_name', // Full name from CSV
        'status',
        'reason',
        'attended',
        'confirmation_date',
    ];

    protected $casts = [
        'confirmation_date' => 'datetime',
        'attended' => 'boolean',
    ];

    public function request()
    {
        return $this->belongsTo(MakeUpClassRequest::class, 'make_up_class_request_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
