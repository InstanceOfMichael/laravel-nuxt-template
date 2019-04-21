<?php

namespace App\Jobs;

use App\Question;
use App\Side;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateQuestionStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var $question Question
     * @var $stats string[]|null update specific stats on question
     *                           or use null to update all
     */

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Question $question, array $stats = null)
    {
        $this->question = $question;
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
            $query = $this->question->comments()->getQuery()->getQuery()
                ->selectRaw('count(*) as aggregate');
            $updates[] = 'comments_count = ('.$query->toSql().')';
            $bindings = array_merge($bindings, $query->getBindings());
        }
        if (is_null($this->stats) || in_array('sides_count', $this->stats)) {
            if ($this->question->sides_type === Side::TYPE_NONE) {
                // side type none has no concept of sides
                $updates[] = 'sides_count = ?';
                $bindings = array_merge($bindings, [0]);
            } elseif ($this->question->sides_type === Side::TYPE_ANY) {
                // side type any shows sides count from existing claims
                $query = $this->question->answers()->getQuery()->getQuery()
                    ->selectRaw('count(distinct answers.side_id) as aggregate');
                $updates[] = 'sides_count = ('.$query->toSql().')';
                $bindings = array_merge($bindings, $query->getBindings());
            } elseif ($this->question->sides_type === Side::TYPE_ALLOW) {
                // side type any shows sides count from allowed sides list
                $query = $this->question->allowedsides()->getQuery()->getQuery()
                    ->selectRaw('count(*) as aggregate');
                $updates[] = 'sides_count = ('.$query->toSql().')';
                $bindings = array_merge($bindings, $query->getBindings());
            }
        }
        if (is_null($this->stats) || in_array('answers_count', $this->stats)) {
            $query = $this->question->answers()->getQuery()->getQuery()
                ->selectRaw('count(*) as aggregate');
            $updates[] = 'answers_count = ('.$query->toSql().')';
            $bindings = array_merge($bindings, $query->getBindings());
        }
        if (is_null($this->stats) || in_array('topics_count', $this->stats)) {
            $query = $this->question->questiontopics()->getQuery()->getQuery()
                ->selectRaw('count(*) as aggregate');
            $updates[] = 'topics_count = ('.$query->toSql().')';
            $bindings = array_merge($bindings, $query->getBindings());
        }
        if (count($updates)) {
            $bindings = array_merge($bindings, [$this->question->id]);
            DB::update('UPDATE questions'
                .' SET '.implode(', ', $updates)
                .' WHERE questions.id = ?', $bindings);

            // @todo broadcast a patch update
        }
    }
}
