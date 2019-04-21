<?php

namespace App\Concerns;

use App\Question;

trait HasQuestionId {

    /**
     * Get the question associated with this object
     */
    public function question ()
    {
        return $this->belongsTo(Question::class);
    }

    public function scopeWhereQuestionId ($query, $value) {
        return $query->whereRelationSearch($this->question(), $value);
    }
}
