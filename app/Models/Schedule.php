<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedule';

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'assignment_id',
        'room_id',
    ];

    public function assignment() {
        return $this->belongsTo(Assignment::class);
    }

    public function room() {
        return $this->belongsTo(Room::class);
    }

    public function keyLending()
    {
        return $this->hasMany(KeyLending::class);
    }

}
