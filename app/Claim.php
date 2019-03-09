<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model implements Commentable
{
    use SerializesDates;

    protected $fillable = [
        'title',
        'text',
        'op_id',
    ];

    /**
     * Get the original poster (User) associated with the claim.
     */
    public function op()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the comments associated with the claim.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'topic');
    }

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
