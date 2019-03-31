<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use Traits\SerializesDates,
        Traits\HasOpId;

    protected $fillable = [
        'name',
        'text',
        'op_id',
    ];

    /**
     * Get the memberships associated with this model.
     */
    public function groupmemberships()
    {
        return $this->hasMany(Groupmembership::class);
    }

    /**
     * Get the subscriptions associated with this model.
     */
    public function groupsubscriptions()
    {
        return $this->hasMany(Groupsubscription::class);
    }

}
