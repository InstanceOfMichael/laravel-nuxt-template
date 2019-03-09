<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
    implements
        Contracts\Commentable,
        Contracts\HasOp
{
    use Traits\SerializesDates,
        Traits\HasComments,
        Traits\HasOpId;

    protected $fillable = [
        'title',
        'text',
        'sides_type',
        'op_id',
    ];

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
