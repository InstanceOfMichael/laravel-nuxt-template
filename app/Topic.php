<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
    implements
        Contracts\HasComments,
        Contracts\HasOp
{
    use Traits\SerializesDates,
        Traits\HasOpId;
    //
}
