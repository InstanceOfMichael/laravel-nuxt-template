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
        if (is_array($value)) {
            $query->whereIn($this->claim()->getQualifiedForeignKey(), $value);
        } elseif ($value) {
            $query->where($this->claim()->getQualifiedForeignKey(), $value);
        }
    }
}
