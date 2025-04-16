<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'icon',
    ];

    protected $hidden = [

    ];

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }
}
