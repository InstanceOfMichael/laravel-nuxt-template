<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Comment extends Model
    implements
        Contracts\HasOp
{
    use Traits\SerializesDates,
        Traits\HasOpId;

    protected $attributes = [
        'pc_id' => 0, // using 0 as null to not bloat column definition
    ];
    protected $fillable = [
        'text',
        'op_id',
        'pc_id',
        'topic_id',
        'topic_type',
    ];

    /**
     * Get the original poster (User) associated with the question.
     */
    public function parentComment()
    {
        return $this->belongsTo(Comment::class, 'pc_id');
    }

    /**
     * Get the original poster (User) associated with the question.
     */
    public function childComments()
    {
        return $this->hasMany(Comment::class, 'pc_id');
    }

    /**
     * Get the original poster (User) associated with the question.
     */
    public function topic()
    {
        return $this->morphTo();
    }

}
