<?php

namespace App;

use App\Observers\LinkObserver;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
    implements
        Contracts\HasComments,
        Contracts\HasOp
{
    use Concerns\SerializesDates,
        Concerns\HasComments,
        Concerns\HasOpId;

    protected $fillable = [
        'title',
        'url',
        'op_id',
    ];

    protected $hidden = [
        'meta',
    ];

    public static function boot() {
        parent::boot();
        static::observe(new LinkObserver());
    }

    /**
     * Get the comments associated with the linkdomain.
     */
    public function linkdomain()
    {
        return $this->belongsTo(Linkdomain::class, 'ld_id');
    }

}
