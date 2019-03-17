<?php

namespace App\Traits;

use App\Question;

trait HasQuestionId {

    /**
     * Get the question associated with this object
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function scopeWhereQuestionId ($query, $value) {
        if (is_array($value)) {
            $query->whereIn($this->qualifyColumn('question_id'), $value);
        } elseif ($value) {
            $query->where($this->qualifyColumn('question_id'), $value);
        }
    }
}
