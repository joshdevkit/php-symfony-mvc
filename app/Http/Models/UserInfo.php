<?php

namespace App\Http\Models;

use Core\Databases\Database\Model;

class UserInfo extends Model
{
    protected $table = 'user_info';

    protected $fillable = [
        'user_id',
        'contact',
        'address',
        'citizenship',
        'profile_picture'
    ];
}
