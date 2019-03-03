<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model implements Commentable
{
    use SerializesDates;

    protected $fillable = [
        'question_id',
        'claim_id',
        'op_id',
    ];

    /**
     * Get the original poster (User) who associated the claim to the question.
     * This user could be different from the one who created the claim
     */
    public function op()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the question associated with this answer
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the claim associated with this answer
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Get the comments associated with the question.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'topic');
    }
}
