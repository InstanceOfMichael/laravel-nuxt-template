<?php

namespace App\Traits;

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
        if (is_array($value)) {
            $query->whereIn($this->user()->getQualifiedForeignKey(), $value);
        } elseif ($value) {
            $query->where($this->user()->getQualifiedForeignKey(), $value);
        }
    }
}
