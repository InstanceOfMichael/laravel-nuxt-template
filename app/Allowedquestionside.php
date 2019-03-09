<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allowedquestionside extends Model
{
    protected $fillable = [
        'side_id',
        'op_id',
        'question_id',
    ];
    /**
     * Get the original poster (User) associated with the allowedquestionside.
     */
    public function op()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the question associated with the allowedquestionside.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the side associated with the allowedquestionside.
     */
    public function side()
    {
        return $this->belongsTo(Side::class);
    }
}
