<?php

namespace App\Concerns;

use App\User;

trait HasOpId {

    /**
     * Get the original poster (User) associated with this.
     */
    public function op ()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhereOpId ($query, $value) {
        return $query->whereRelationSearch($this->op(), $value);
    }

}
