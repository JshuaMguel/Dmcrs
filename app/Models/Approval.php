<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $fillable = [
        'make_up_class_request_id',
        'chair_id',
        'decision',
        'remarks',
    ];

    public function request()
    {
        return $this->belongsTo(MakeUpClassRequest::class, 'make_up_class_request_id');
    }

    public function chair()
    {
        return $this->belongsTo(User::class, 'chair_id');
    }
}
