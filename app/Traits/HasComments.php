<?php

namespace App\Traits;

use App\Comment;

trait HasComments {

    /**
     * Get the comments associated with this model.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'context');
    }
}
