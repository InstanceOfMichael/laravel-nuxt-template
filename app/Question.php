<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Observers\QuestionObserver;

class Question extends Model
    implements
        Contracts\HasComments,
        Contracts\HasOp
{
    use Concerns\SerializesDates,
        Concerns\HasComments,
        Concerns\HasOpId;

    protected $fillable = [
        'title',
        'text',
        'sides_type',
        'op_id',
    ];

    protected $attributes = [
        'comments_count' => 0,
        'answers_count' => 0,
        'sides_count' => 0,
        'topics_count' => 0,
    ];

    public static function boot() {
        parent::boot();
        static::observe(new QuestionObserver());
    }

    /**
     * Get the answers associated with the question.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the allowedsides associated with the question.
     */
    public function allowedsides()
    {
        return $this->hasMany(Allowedquestionside::class);
    }

    /**
     * Get the questiontopics associated with the question.
     */
    public function questiontopics()
    {
        return $this->hasMany(Questiontopic::class);
    }
}
