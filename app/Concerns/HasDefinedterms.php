<?php

namespace App\Concerns;

use App\Definedterm;

trait HasDefinedterms {

    /**
     * Get the definedterms associated with this model.
     */
    public function definedterms ()
    {
        return $this->morphMany(Definedterm::class, 'context');
    }
}
