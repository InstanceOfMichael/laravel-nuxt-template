<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Side extends Model implements Commentable
{
    // question->side_type options:
    public const TYPE_NONE  = 0; // no sides, do not display anything
    public const TYPE_ANY   = 1; // answers can pick literally any side
    public const TYPE_ALLOW = 2; // sides from allowedquestionsides table are allowed

    use SerializesDates;

    protected $fillable = [
        'name',
        'text',
        'op_id',
    ];

    /**
     * Get the original poster (User) associated with the question.
     */
    public function op()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments associated with the question.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'topic');
    }

    /**
     * Get the answers associated with the question.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the answers associated with the question.
     */
    public function allowedquestionsides()
    {
        return $this->hasMany(Allowedanswerside::class);
    }

}
