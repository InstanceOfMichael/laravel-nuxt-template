<?php

namespace App\Concerns;

use App\Group;

trait HasGroupId {

    /**
     * Get the group associated with this object
     */
    public function group ()
    {
        return $this->belongsTo(Group::class);
    }

    public function scopeWhereGroupId ($query, $value) {
        return $query->whereRelationSearch($this->group(), $value);
    }
}
