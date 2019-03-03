<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Commentable;

class ExistingParentCommentId implements Rule
{
    /** @var \App\Commentable */
    protected $topic;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Commentable $topic)
    {
        $this->topic = $topic;
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
        return $this->topic->comments()->where('comments.id', $value)->take(1)->count() > 0;
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
