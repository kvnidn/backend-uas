<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignment';

    protected $fillable = [
        'user_id',
        'kelas_id',
    ];

    // public function subject() {
    //     return $this->belongsTo(Subject::class);
    // }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function kelas() {
        return $this->belongsTo(Kelas::class);
    }

    public function schedule() {
        return $this->hasMany(Schedule::class);
    }

}
