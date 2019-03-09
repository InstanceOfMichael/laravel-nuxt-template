<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OnlyCaseChange implements Rule
{
    protected $model;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($model = null)
    {
        $this->model = $model;
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
        if (strtolower($this->model->{$attribute}) === strtolower($value)) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The value must be the same (case insensitive).';
    }
}
