<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // protected $guarded = [
    //     'id',
    // ];

    public function assignment() {
        return $this->hasMany(Assignment::class);
    }
}
