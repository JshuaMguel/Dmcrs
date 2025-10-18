<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $fillable = ['name', 'location', 'capacity'];

    /**
     * Get the schedules for the room.
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'room', 'name');
    }
}
