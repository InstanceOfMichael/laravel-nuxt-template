<?php

namespace App\Traits;

use App\User;

trait HasOpId {

    /**
     * Get the original poster (User) associated with the allowedquestionside.
     */
    public function op()
    {
        return $this->belongsTo(User::class);
    }
}
