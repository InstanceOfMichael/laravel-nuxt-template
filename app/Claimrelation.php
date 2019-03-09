<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claimrelation extends Model
    implements
        Contracts\Commentable,
        Contracts\HasOp
{
    use Traits\SerializesDates,
        Traits\HasComments,
        Traits\HasOpId;

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
}
