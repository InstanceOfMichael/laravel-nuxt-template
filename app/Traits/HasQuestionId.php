<?php

namespace App\Traits;

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
        if (is_array($value)) {
            $query->whereIn($this->question()->getQualifiedForeignKey(), $value);
        } elseif ($value) {
            $query->where($this->question()->getQualifiedForeignKey(), $value);
        }
    }
}
