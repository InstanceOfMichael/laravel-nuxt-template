<?php

namespace App\Concerns;

use App\Definition;

trait HasDefinitionId {

    /**
     * Get the definition associated with this object
     */
    public function definition ()
    {
        return $this->belongsTo(Definition::class);
    }

    public function scopeWhereDefinitionId ($query, $value) {
        if (is_array($value)) {
            $query->whereIn($this->definition()->getQualifiedForeignKey(), $value);
        } elseif ($value) {
            $query->where($this->definition()->getQualifiedForeignKey(), $value);
        }
    }
}
