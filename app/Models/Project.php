<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'image',
        'short_desc',
        'full_desc',
        'type',
        'github',
        'url',
    ];

    protected $hidden = [

    ];

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }
}
