<?php

namespace App;

use App\Observers\LinkObserver;
use Illuminate\Database\Eloquent\Model;

class Link extends Model implements Commentable
{
    protected $fillable = [
        'title',
        'url',
        'op_id',
    ];

    protected $hidden = [
        'meta',
    ];

    public static function boot() {
        parent::boot();
        static::observe(new LinkObserver());
    }

    /**
     * Get the comments associated with the linkdomain.
     */
    public function linkdomain()
    {
        return $this->belongsTo(Linkdomain::class, 'ld_id');
    }

    /**
     * Get the comments associated with the link.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'topic');
    }

    /**
     * Get the original poster (User) associated with the link.
     */
    public function op()
    {
        return $this->belongsTo(User::class);
    }

}
