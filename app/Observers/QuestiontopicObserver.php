<?php

namespace App\Observers;

use App\Questiontopic;
use App\Jobs\UpdateQuestionStats;

class QuestiontopicObserver
{
    /**
     * Handle the questiontopic "created" event.
     *
     * @param  \App\Questiontopic  $questiontopic
     * @return void
     */
    public function created(Questiontopic $questiontopic)
    {
        dispatch_now(new UpdateQuestionStats($questiontopic->question, [
            'topics_count',
        ]));
    }

    /**
     * Handle the questiontopic "updated" event.
     *
     * @param  \App\Questiontopic  $questiontopic
     * @return void
     */
    public function updated(Questiontopic $questiontopic)
    {
        //
    }

    /**
     * Handle the questiontopic "deleted" event.
     *
     * @param  \App\Questiontopic  $questiontopic
     * @return void
     */
    public function deleted(Questiontopic $questiontopic)
    {
        dispatch_now(new UpdateQuestionStats($questiontopic->question, [
            'topics_count',
        ]));
    }

    /**
     * Handle the questiontopic "restored" event.
     *
     * @param  \App\Questiontopic  $questiontopic
     * @return void
     */
    public function restored(Questiontopic $questiontopic)
    {
        dispatch_now(new UpdateQuestionStats($questiontopic->question, [
            'topics_count',
        ]));
    }

    /**
     * Handle the questiontopic "force deleted" event.
     *
     * @param  \App\Questiontopic  $questiontopic
     * @return void
     */
    public function forceDeleted(Questiontopic $questiontopic)
    {
        dispatch_now(new UpdateQuestionStats($questiontopic->question, [
            'topics_count',
        ]));
    }
}
