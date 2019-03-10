<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StartsWith implements Rule
{
    /**
     * @var  array|string  $needle
     */
    protected $needle;

    /**
     * Create a new rule instance.
     *
     * @param  array|string  $needle
     * @return void
     */
    public function __construct($needle)
    {
        $this->needle = $needle;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return starts_with($value, $this->needle);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if (is_string($this->needle)) {
            return 'The :attribute has to start with: '.$this->needle;
        }
        return 'The :attribute has to start with one of: '.implode(', ', $this->needle);
    }
}
