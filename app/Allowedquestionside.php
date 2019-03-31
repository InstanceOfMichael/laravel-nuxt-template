<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allowedquestionside extends Model
    implements
        Contracts\HasOp
{
    use Concerns\HasOpId,
        Concerns\HasQuestionId,
        Concerns\SerializesDates;

    protected $fillable = [
        'side_id',
        'op_id',
        'question_id',
    ];

    /**
     * Get the question associated with the allowedquestionside.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the side associated with the allowedquestionside.
     */
    public function side()
    {
        return $this->belongsTo(Side::class);
    }
}
