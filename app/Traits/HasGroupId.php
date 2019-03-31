<?php

namespace App\Traits;

use App\Group;

trait HasGroupId {

    /**
     * Get the user associated with this object
     */
    public function group ()
    {
        return $this->belongsTo(Group::class);
    }

    public function scopeWhereGroupId ($query, $value) {
        if (is_array($value)) {
            $query->whereIn($this->group()->getQualifiedForeignKey(), $value);
        } elseif ($value) {
            $query->where($this->group()->getQualifiedForeignKey(), $value);
        }
    }
}
