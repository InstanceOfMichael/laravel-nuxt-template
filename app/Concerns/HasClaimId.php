<?php

namespace App\Concerns;

use App\Claim;

trait HasClaimId {

    /**
     * Get the claim associated with this object
     */
    public function claim ()
    {
        return $this->belongsTo(Claim::class);
    }

    public function scopeWhereClaimId ($query, $value) {
        return $query->whereRelationSearch($this->claim(), $value);
    }
}
