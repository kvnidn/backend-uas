<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'prodi',
        'subject_id',
        'class',
    ];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }

    public function assignment() {
        return $this->hasMany(Assignment::class);
    }
}
