<?php

namespace App\Observers;

use App\Claimtopic;
use App\Jobs\UpdateClaimStats;
use App\Question;

class ClaimtopicObserver
{
    /**
     * Handle the claimtopic "created" event.
     *
     * @param  \App\Claimtopic  $claimtopic
     * @return void
     */
    public function created(Claimtopic $claimtopic)
    {
        dispatch_now(new UpdateClaimStats($claimtopic->claim, [
            'topics_count',
        ]));
    }

    /**
     * Handle the claimtopic "updated" event.
     *
     * @param  \App\Claimtopic  $claimtopic
     * @return void
     */
    public function updated(Claimtopic $claimtopic)
    {
        //
    }

    /**
     * Handle the claimtopic "deleted" event.
     *
     * @param  \App\Claimtopic  $claimtopic
     * @return void
     */
    public function deleted(Claimtopic $claimtopic)
    {
        dispatch_now(new UpdateClaimStats($claimtopic->claim, [
            'topics_count',
        ]));
    }

    /**
     * Handle the claimtopic "restored" event.
     *
     * @param  \App\Claimtopic  $claimtopic
     * @return void
     */
    public function restored(Claimtopic $claimtopic)
    {
        dispatch_now(new UpdateClaimStats($claimtopic->claim, [
            'topics_count',
        ]));
    }

    /**
     * Handle the claimtopic "force deleted" event.
     *
     * @param  \App\Claimtopic  $claimtopic
     * @return void
     */
    public function forceDeleted(Claimtopic $claimtopic)
    {
        dispatch_now(new UpdateClaimStats($claimtopic->claim, [
            'topics_count',
        ]));
    }
}
