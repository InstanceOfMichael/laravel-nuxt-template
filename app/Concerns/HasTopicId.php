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
        return $query->whereRelationSearch($this->topic(), $value);
    }
}
