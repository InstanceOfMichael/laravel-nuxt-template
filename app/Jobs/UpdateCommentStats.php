<?php

namespace App\Jobs;

use App\Comment;
use App\Side;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateCommentStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $comment Comment
     * @var $stats string[]|null update specific stats on comment
     *                           or use null to update all
     */

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Comment $comment, array $stats = null)
    {
        $this->comment = $comment;
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
        if (is_null($this->stats) || in_array('reply_count', $this->stats)) {
            $query = $this->comment->childComments()->getQuery()->getQuery()
                ->selectRaw('count(*) as aggregate');
            $updates[] = 'reply_count = ('.$query->toSql().')';
            $bindings = array_merge($bindings, $query->getBindings());
        }
        if (count($updates)) {
            $bindings = array_merge($bindings, [$this->comment->id]);
            DB::update('UPDATE comments'
                .' SET '.implode(', ', $updates)
                .' WHERE comments.id = ?', $bindings);

            // @todo broadcast a patch update
        }
    }
}
