<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Definition extends Model
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
    public function claimdefinitions()
    {
        return $this->hasMany(Claimdefinition::class);
    }

    /**
     * Get the answers associated with the question.
     */
    public function questiondefinitions()
    {
        return $this->hasMany(Questiondefinition::class);
    }
}
