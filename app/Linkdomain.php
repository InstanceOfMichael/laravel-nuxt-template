<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Linkdomain extends Model
    implements
        Contracts\HasComments,
        Contracts\HasOp
{
    use Concerns\SerializesDates,
        Concerns\HasComments,
        Concerns\HasOpId;

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
