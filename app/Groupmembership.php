<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Groupmembership extends Model
    implements
        Contracts\HasGroup,
        Contracts\HasUser
{
    use Concerns\SerializesDates,
        Concerns\HasGroupId,
        Concerns\HasUserId;
    //

    protected $fillable = [
        'group_id',
        'user_id',
    ];
}
