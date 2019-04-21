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

    protected $attributes = [
        'comments_count' => 0,
        'answers_count' => 0,
        'sides_count' => 0,
        'topics_count' => 0,
    ];

    /**
     * Get the answers associated with the claim.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the replyclaims associated with this claim
     */
    public function replyclaims()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Get the parentclaims associated with this answer
     */
    public function parentclaims()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Get the claimsides associated with the claim.
     */
    public function claimsides()
    {
        return $this->hasMany(Claimside::class);
    }

    /**
     * Get the claimtopics associated with the claim.
     */
    public function claimtopics()
    {
        return $this->hasMany(Claimtopic::class);
    }
}
