<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'institution',
        'start_date',
        'end_date',
        'hours',
        'description',
        'url',
    ];

    protected $hidden = [

    ];

}
