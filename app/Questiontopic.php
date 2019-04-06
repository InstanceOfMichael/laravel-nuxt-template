<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questiontopic extends Model
    implements
        Contracts\HasOp,
        Contracts\HasQuestion,
        Contracts\HasTopic
{
    use Concerns\HasOpId,
        Concerns\HasQuestionId,
        Concerns\HasTopicId,
        Concerns\SerializesDates;

    protected $fillable = [
        'topic_id',
        'question_id',
        'op_id',
    ];
}
