<?php

namespace App\Concerns;

use App\Side;

trait HasSideId {

    /**
     * Get the side associated with this object
     */
    public function side ()
    {
        return $this->belongsTo(Side::class);
    }

    public function scopeWhereTopicId ($query, $value) {
        return $query->whereRelationSearch($this->side(), $value);
    }
}
