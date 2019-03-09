<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Claimside extends Model
{
    use SerializesDates;

    protected $fillable = [
        'question_id',
        'side_id',
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
