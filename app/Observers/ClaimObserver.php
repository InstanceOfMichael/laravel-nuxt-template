<?php

namespace App\Observers;

use App\Claim;

class ClaimObserver
{
    /**
     * Handle the claim "created" event.
     *
     * @param  \App\Claim  $claim
     * @return void
     */
    public function created(Claim $claim)
    {
        //
    }

    /**
     * Handle the claim "updated" event.
     *
     * @param  \App\Claim  $claim
     * @return void
     */
    public function updated(Claim $claim)
    {
        //
    }

    /**
     * Handle the claim "deleted" event.
     *
     * @param  \App\Claim  $claim
     * @return void
     */
    public function deleted(Claim $claim)
    {
        dispatch_now(new UpdateClaimStats($claim));
    }

    /**
     * Handle the claim "restored" event.
     *
     * @param  \App\Claim  $claim
     * @return void
     */
    public function restored(Claim $claim)
    {
        dispatch_now(new UpdateClaimStats($claim));
    }

    /**
     * Handle the claim "force deleted" event.
     *
     * @param  \App\Claim  $claim
     * @return void
     */
    public function forceDeleted(Claim $claim)
    {
        //
    }
}
