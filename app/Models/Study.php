<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Study extends Model
{
    protected $fillable = [
        'title',
        'specialty',
        'start_date',
        'end_date',
        'institution',
        'image',
    ];

    protected $hidden = [

    ];
}
