<?php

namespace App\Http\Models;

use Core\Databases\Database\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password'
    ];
}
