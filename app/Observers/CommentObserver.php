<?php

namespace App\Observers;

use App\Comment;
use App\Jobs\UpdateClaimStats;
use App\Jobs\UpdateQuestionStats;
use App\Jobs\UpdateCommentStats;
use App\Claim;
use App\Question;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function creating(Comment $comment)
    {
        if ($comment->pc_id) {
            $comment->depth = $comment->parentComment->depth + 1;
        }
        if ($comment->context instanceof Claim) {
            dispatch_now(new UpdateClaimStats($comment->context, [
                'comments_count',
            ]));
        }
        if ($comment->context instanceof Question) {
            dispatch_now(new UpdateQuestionStats($comment->context, [
                'comments_count',
            ]));
        }
    }

    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */
    public function created(Comment $comment)
    {
        if ($comment->pc_id) {
            dispatch_now(new UpdateCommentStats($comment->parentComment, [
                'reply_count',
            ]));
        }
        if ($comment->context instanceof Claim) {
            dispatch_now(new UpdateClaimStats($comment->context, [
                'comments_count',
            ]));
        }
        if ($comment->context instanceof Question) {
            dispatch_now(new UpdateQuestionStats($comment->context, [
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
        if ($comment->context instanceof Claim) {
            dispatch_now(new UpdateClaimStats($comment->context, [
                'comments_count',
            ]));
        }
        if ($comment->context instanceof Question) {
            dispatch_now(new UpdateQuestionStats($comment->context, [
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
        if ($comment->context instanceof Claim) {
            dispatch_now(new UpdateClaimStats($comment->context, [
                'comments_count',
            ]));
        }
        if ($comment->context instanceof Question) {
            dispatch_now(new UpdateQuestionStats($comment->context, [
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
        if ($comment->context instanceof Claim) {
            dispatch_now(new UpdateClaimStats($comment->context, [
                'comments_count',
            ]));
        }
        if ($comment->context instanceof Question) {
            dispatch_now(new UpdateQuestionStats($comment->context, [
                'comments_count',
            ]));
        }
    }
}
