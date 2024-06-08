<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $table = 'user';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    public function assignment() {
        return $this->hasMany(Assignment::class);
    }
}
