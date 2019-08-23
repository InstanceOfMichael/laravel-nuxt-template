<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
    implements
        Contracts\HasComments
{
    use Concerns\SerializesDates,
        Concerns\HasComments;

    protected $fillable = [
        'name',
        'text',
    ];

    /**
     * Get the answers associated with the question.
     */
    public function claimtopics()
    {
        return $this->hasMany(Claimtopic::class);
    }

    /**
     * Get the answers associated with the question.
     */
    public function questiontopics()
    {
        return $this->hasMany(Questiontopic::class);
    }

    public function scopeWhereFromRequest ($query, Request $request) {
        if ($request->topic_id) {

        }
        return $query;
    }
}
