<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        'featured',
    ];

    protected $hidden = [

    ];

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }
}
