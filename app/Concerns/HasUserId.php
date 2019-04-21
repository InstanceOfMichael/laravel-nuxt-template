<?php

namespace App\Concerns;

use App\User;

trait HasUserId {

    /**
     * Get the user associated with this object
     */
    public function user ()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhereUserId ($query, $value) {
        return $query->whereRelationSearch($this->user(), $value);
    }
}
