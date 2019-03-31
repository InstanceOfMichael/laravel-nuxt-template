<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
    implements
        Contracts\HasComments,
        Contracts\HasOp
{
    use Concerns\SerializesDates,
        Concerns\HasOpId;
    //
}
