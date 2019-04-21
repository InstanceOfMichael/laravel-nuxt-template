<?php

namespace App\Jobs;

use App\Claim;
use App\Side;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateClaimStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $claim Claim
     * @var $stats string[]|null update specific stats on claim
     *                           or use null to update all
     */

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Claim $claim, array $stats = null)
    {
        $this->claim = $claim;
        $this->stats = $stats;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $bindings = [];
        $updates = [];
        if (is_null($this->stats) || in_array('comments_count', $this->stats)) {
            $query = $this->claim->comments()->getQuery()->getQuery()
                ->selectRaw('count(*) as aggregate');
            $updates[] = 'comments_count = ('.$query->toSql().')';
            $bindings = array_merge($bindings, $query->getBindings());
        }
        if (is_null($this->stats) || in_array('sides_count', $this->stats)) {
            $query = $this->claim->answers()->getQuery()->getQuery()
                ->selectRaw('count(distinct answers.side_id) as aggregate');
            $updates[] = 'sides_count = ('.$query->toSql().')';
            $bindings = array_merge($bindings, $query->getBindings());
        }
        if (is_null($this->stats) || in_array('answers_count', $this->stats)) {
            $query = $this->claim->answers()->getQuery()->getQuery()
                ->selectRaw('count(*) as aggregate');
            $updates[] = 'answers_count = ('.$query->toSql().')';
            $bindings = array_merge($bindings, $query->getBindings());
        }
        if (is_null($this->stats) || in_array('topics_count', $this->stats)) {
            $query = $this->claim->claimtopics()->getQuery()->getQuery()
                ->selectRaw('count(*) as aggregate');
            $updates[] = 'topics_count = ('.$query->toSql().')';
            $bindings = array_merge($bindings, $query->getBindings());
        }
        if (count($updates)) {
            $bindings = array_merge($bindings, [$this->claim->id]);
            DB::update('UPDATE claims'
                .' SET '.implode(', ', $updates)
                .' WHERE claims.id = ?', $bindings);

            // @todo broadcast a patch update
        }
    }
}
