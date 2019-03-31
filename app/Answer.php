<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Observers\AnswerObserver;

class Answer extends Model
    implements
        Contracts\HasComments,
        Contracts\HasOp
{
    use Concerns\SerializesDates,
        Concerns\HasComments,
        Concerns\HasOpId,
        Concerns\HasQuestionId;

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
     * Get the claim associated with this answer
     */
    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function scopeWhereRequest ($query, $request) {
        $arr = $request->all();
        if (array_key_exists('question_id', $arr)) {
            $this->scopeWhereQuestionId($query, $arr['question_id']);
        }
    }
}
