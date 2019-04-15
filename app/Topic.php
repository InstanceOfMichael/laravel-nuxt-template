<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
