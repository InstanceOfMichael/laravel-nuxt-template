<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claimtopic extends Model
    implements
        Contracts\HasOp,
        Contracts\HasClaim,
        Contracts\HasTopic
{
    use Concerns\HasOpId,
        Concerns\HasClaimId,
        Concerns\HasTopicId,
        Concerns\SerializesDates;

    protected $fillable = [
        'topic_id',
        'claim_id',
        'op_id',
    ];
}
