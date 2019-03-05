<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Linkdomain extends Model implements Commentable
{
    protected $fillable = [
        'name',
        'domain',
        'text',
    ];

    protected $hidden = [
        'meta',
    ];

    /**
     * Get the comments associated with the linkdomain.
     */
    public function links()
    {
        return $this->hasMany(Link::class, 'ld_id');
    }

    /**
     * Get the comments associated with the linkdomain.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'topic');
    }
}
