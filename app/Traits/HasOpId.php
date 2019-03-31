<?php

namespace App\Traits;

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
        if (is_array($value)) {
            $query->whereIn($this->op()->getQualifiedForeignKey(), $value);
        } elseif ($value) {
            $query->where($this->op()->getQualifiedForeignKey(), $value);
        }
    }

}
