<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model implements Commentable
{
    use SerializesDates;

    protected $fillable = [
        'title',
        'text',
        'sides_type',
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
     * Get the allowedsides associated with the question.
     */
    public function allowedsides()
    {
        return $this->hasMany(Allowedquestionside::class);
    }
}
