<?php

namespace App\Concerns;

use DateTimeInterface;

trait SerializesDates
{
    /**
     * Prepare a date for array / JSON serialization.
     *
     * @link https://github.com/laravel/framework/issues/21703
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return intval($date->jsonSerialize());
    }
}
