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
        if (is_array($value)) {
            $query->whereIn($this->side()->getQualifiedForeignKey(), $value);
        } elseif ($value) {
            $query->where($this->side()->getQualifiedForeignKey(), $value);
        }
    }
}
