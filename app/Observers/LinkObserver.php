<?php

namespace App\Observers;

use App\Link;
use App\Linkdomain;

class LinkObserver
{
    /**
     * Handle the link "created" event.
     *
     * @param  \App\Link  $link
     * @return void
     */
    public function creating(Link $link)
    {
        if (!$link->ld_id) {
            $parse = parse_url($link->url);
            $link->linkdomain()->associate(Linkdomain::firstOrCreate([
                'domain' => $parse['host'],
            ]));
        }
    }

    /**
     * Handle the link "created" event.
     *
     * @param  \App\Link  $link
     * @return void
     */
    public function created(Link $link)
    {
        //
    }

    /**
     * Handle the link "updated" event.
     *
     * @param  \App\Link  $link
     * @return void
     */
    public function updated(Link $link)
    {
        //
    }

    /**
     * Handle the link "deleted" event.
     *
     * @param  \App\Link  $link
     * @return void
     */
    public function deleted(Link $link)
    {
        //
    }

    /**
     * Handle the link "restored" event.
     *
     * @param  \App\Link  $link
     * @return void
     */
    public function restored(Link $link)
    {
        //
    }

    /**
     * Handle the link "force deleted" event.
     *
     * @param  \App\Link  $link
     * @return void
     */
    public function forceDeleted(Link $link)
    {
        //
    }
}
