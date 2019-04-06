<?php

namespace App\Concerns;

use App\Topic;

trait HasTopicId {

    /**
     * Get the topic associated with this object
     */
    public function topic ()
    {
        return $this->belongsTo(Topic::class);
    }

    public function scopeWhereTopicId ($query, $value) {
        if (is_array($value)) {
            $query->whereIn($this->topic()->getQualifiedForeignKey(), $value);
        } elseif ($value) {
            $query->where($this->topic()->getQualifiedForeignKey(), $value);
        }
    }
}
