<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Observers\AnswerObserver;

class Answer extends Model
    implements
        Contracts\Commentable,
        Contracts\HasOp
{
    use Traits\SerializesDates,
        Traits\HasComments,
        Traits\HasOpId;

    protected $fillable = [
        'question_id',
        'claim_id',
        'op_id',
    ];

    public static function boot() {
        parent::boot();
        static::observe(new AnswerObserver());
    }

    /**
     * Get the question associated with this answer
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the claim associated with this answer
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
