<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
    implements
        Contracts\HasComments,
        Contracts\HasOp
{
    use Concerns\SerializesDates,
        Concerns\HasComments,
        Concerns\HasOpId;

    protected $fillable = [
        'title',
        'text',
        'op_id',
    ];

    /**
     * Get the answers associated with the claim.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the question associated with this answer
     */
    public function replyclaims()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Get the claim associated with this answer
     */
    public function parentclaims()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Get the allowedsides associated with the question.
     */
    public function claimsides()
    {
        return $this->hasMany(Claimside::class);
    }
}
