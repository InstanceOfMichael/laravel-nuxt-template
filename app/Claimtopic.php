<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Observers\ClaimtopicObserver;

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

    public static function boot() {
        parent::boot();
        static::observe(new ClaimtopicObserver());
    }
}
