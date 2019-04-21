<?php

namespace App\Observers;

use App\Answer;
use App\Jobs\UpdateClaimStats;
use App\Jobs\UpdateQuestionStats;
use App\Question;

class AnswerObserver
{
    /**
     * Handle the answer "created" event.
     *
     * @param  \App\Answer  $answer
     * @return void
     */
    public function created(Answer $answer)
    {
        dispatch_now(new UpdateQuestionStats($answer->question, [
            'answers_count',
        ]));
        dispatch_now(new UpdateClaimStats($answer->claim, [
            'answers_count',
        ]));
    }

    /**
     * Handle the answer "updated" event.
     *
     * @param  \App\Answer  $answer
     * @return void
     */
    public function updated(Answer $answer)
    {
        //
    }

    /**
     * Handle the answer "deleted" event.
     *
     * @param  \App\Answer  $answer
     * @return void
     */
    public function deleted(Answer $answer)
    {
        dispatch_now(new UpdateQuestionStats($answer->question, [
            'answers_count',
        ]));
        dispatch_now(new UpdateClaimStats($answer->claim, [
            'answers_count',
        ]));
    }

    /**
     * Handle the answer "restored" event.
     *
     * @param  \App\Answer  $answer
     * @return void
     */
    public function restored(Answer $answer)
    {
        dispatch_now(new UpdateQuestionStats($answer->question, [
            'answers_count',
        ]));
        dispatch_now(new UpdateClaimStats($answer->claim, [
            'answers_count',
        ]));
    }

    /**
     * Handle the answer "force deleted" event.
     *
     * @param  \App\Answer  $answer
     * @return void
     */
    public function forceDeleted(Answer $answer)
    {
        dispatch_now(new UpdateQuestionStats($answer->question, [
            'answers_count',
        ]));
        dispatch_now(new UpdateClaimStats($answer->claim, [
            'answers_count',
        ]));
    }
}
