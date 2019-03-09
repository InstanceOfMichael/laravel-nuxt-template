<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Linkdomain extends Model
    implements
        Contracts\Commentable,
        Contracts\HasOp
{
    use Traits\SerializesDates,
        Traits\HasComments,
        Traits\HasOpId;

    protected $fillable = [
        'name',
        'domain',
        'text',
    ];

    protected $hidden = [
        'meta',
    ];

    /**
     * Get the comments associated with the linkdomain.
     */
    public function links()
    {
        return $this->hasMany(Link::class, 'ld_id');
    }

}
