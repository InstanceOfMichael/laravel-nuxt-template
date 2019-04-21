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
        return $query->whereRelationSearch($this->definition(), $value);
    }
}
