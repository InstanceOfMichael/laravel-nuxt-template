<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claimside extends Model
    implements
        Contracts\Commentable,
        Contracts\HasOp
{
    use Traits\SerializesDates,
        Traits\HasComments,
        Traits\HasOpId;

    protected $fillable = [
        'claim_id',
        'side_id',
        'op_id',
    ];

    /**
     * Get the claim associated with the claimside.
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    /**
     * Get the side associated with the claimside.
     */
    public function side()
    {
        return $this->belongsTo(Side::class);
    }
}
