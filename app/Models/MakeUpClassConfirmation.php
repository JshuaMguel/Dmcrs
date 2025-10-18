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
        'status',
        'reason',
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
