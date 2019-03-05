<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claimrelation extends Model implements Commentable
{
    use SerializesDates;

    public const REBUTE = 50;
    public const COLLABORATE = 51;
    public const PREMISE = 52;

    protected $fillable = [
        // parent claim
        'pc_id',
        // reply claim
        'rc_id',
        // original poster
        'op_id',
        // original poster
        'type',
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
    public function replyclaim()
    {
        return $this->belongsTo(Claim::class, 'rc_id');
    }

    /**
     * Get the claim associated with this answer
     */
    public function parentclaim()
    {
        return $this->belongsTo(Claim::class, 'pc_id');
    }

    /**
     * Get the comments associated with the question.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'topic');
    }
}
