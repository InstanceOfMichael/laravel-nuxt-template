<?php

namespace App\Observers;

use App\Allowedquestionside;
use App\Jobs\UpdateQuestionStats;
use App\Question;

class AllowedquestionsideObserver
{
    /**
     * Handle the allowedquestionside "created" event.
     *
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return void
     */
    public function created(Allowedquestionside $allowedquestionside)
    {
        dispatch_now(new UpdateQuestionStats($allowedquestionside->question, [
            'sides_count',
        ]));
    }

    /**
     * Handle the allowedquestionside "updated" event.
     *
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return void
     */
    public function updated(Allowedquestionside $allowedquestionside)
    {
        //
    }

    /**
     * Handle the allowedquestionside "deleted" event.
     *
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return void
     */
    public function deleted(Allowedquestionside $allowedquestionside)
    {
        dispatch_now(new UpdateQuestionStats($allowedquestionside->question, [
            'sides_count',
        ]));
    }

    /**
     * Handle the allowedquestionside "restored" event.
     *
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return void
     */
    public function restored(Allowedquestionside $allowedquestionside)
    {
        dispatch_now(new UpdateQuestionStats($allowedquestionside->question, [
            'sides_count',
        ]));
    }

    /**
     * Handle the allowedquestionside "force deleted" event.
     *
     * @param  \App\Allowedquestionside  $allowedquestionside
     * @return void
     */
    public function forceDeleted(Allowedquestionside $allowedquestionside)
    {
        dispatch_now(new UpdateQuestionStats($allowedquestionside->question, [
            'sides_count',
        ]));
    }
}
