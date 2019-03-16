<?php

namespace App\Observers;

use App\Comment;
use App\Jobs\UpdateQuestionStats;
use App\Question;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        if ($comment->topic instanceof Question) {
            dispatch_now(new UpdateQuestionStats($comment->topic, [
                'comments_count',
            ]));
        }
    }

    /**
     * Handle the comment "updated" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function updated(Comment $comment)
    {
        //
    }

    /**
     * Handle the comment "deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function deleted(Comment $comment)
    {
        if ($comment->topic instanceof Question) {
            dispatch_now(new UpdateQuestionStats($comment->topic, [
                'comments_count',
            ]));
        }
    }

    /**
     * Handle the comment "restored" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function restored(Comment $comment)
    {
        if ($comment->topic instanceof Question) {
            dispatch_now(new UpdateQuestionStats($comment->topic, [
                'comments_count',
            ]));
        }
    }

    /**
     * Handle the comment "force deleted" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function forceDeleted(Comment $comment)
    {
        if ($comment->topic instanceof Question) {
            dispatch_now(new UpdateQuestionStats($comment->topic, [
                'comments_count',
            ]));
        }
    }
}
