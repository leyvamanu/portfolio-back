<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'name',
        'role',
        'rating',
        'avatar',
        'content',
    ];

    protected $hidden = [

    ];
}
