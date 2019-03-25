<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Contracts\Commentable;

class ExistingParentCommentId implements Rule
{
    /** @var \App\Commentable */
    protected $context;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Commentable $context)
    {
        $this->context = $context;
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
        if ($value == 0) {
            // zero does not exist, but is a valid parent,
            // because it indicates no parent
            return true;
        }
        return $this->context->comments()->where('comments.id', $value)->take(1)->get()->count() > 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Replying to comment that does not exist.';
    }
}
