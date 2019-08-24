<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Observers\CommentObserver;

class Comment extends Model
    implements
        Contracts\HasOp
{
    use Concerns\SerializesDates,
        Concerns\HasOpId;

    protected $attributes = [
        'pc_id' => 0, // using 0 as null to not bloat column definition
    ];
    protected $fillable = [
        'text',
        'op_id',
        'pc_id',
        'context_id',
        'context_type',
    ];

    public static function boot() {
        parent::boot();
        static::observe(new CommentObserver());
    }

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
    public function context()
    {
        return $this->morphTo();
    }

    public function scopeWherePcId ($query, $value) {
        return $query->whereRelationSearch($this->parentComment(), $value);
    }

}
