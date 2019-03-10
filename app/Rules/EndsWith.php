<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EndsWith implements Rule
{
    /**
     * @var  array|string  $needle
     */
    protected $needle;

    /**
     * Create a new rule instance.
     *
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
        return ends_with($value, $this->needle);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if (is_string($this->needle)) {
            return 'The :attribute has to end with: '.$this->needle;
        }
        return 'The :attribute has to end with one of: '.implode(', ', $this->needle);
    }
}
