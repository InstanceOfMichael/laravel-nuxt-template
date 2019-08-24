<?php

namespace Tests\Unit\Jobs;

use App\Comment;
use App\Jobs\UpdateCommentStats;
use App\User;
use Tests\TestCase;

class UpdateCommentStatsTest extends TestCase
{

    private function stats (Comment $comment) {
        return [
            'reply_count' => $comment->reply_count,
        ];
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCommentWithNoRelations()
    {
        $user = factory(User::class)->create();
        $comment = $user->comments()->create(factory(Comment::class)->raw());

        dispatch_now(new UpdateCommentStats($comment));

        $this->assertEquals([
            'reply_count' => 0,
        ], $this->stats($comment->fresh()));
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCommentWithTwoChildComments()
    {
        $user = factory(User::class)->create();
        $comment = $user->comments()->create(factory(Comment::class)->raw());

        $otherComments = collect(
            factory(Comment::class, 3)->raw()
        )->map(function (array $raw) use ($user):Comment {
            return $user->comments()->create($raw);
        });
        $replies = collect(factory(Comment::class, 3)->raw([
            'pc_id' => $comment->first()->id,
        ]))->map(function (array $raw) use ($user):Comment {
            return $user->comments()->create($raw);
        });
        $otherReplies = collect(factory(Comment::class, 3)->raw([
            'pc_id' => $otherComments->first()->id,
        ]))->map(function (array $raw) use ($user):Comment {
            return $user->comments()->create($raw);
        });

        // non-direct replies are not counted
        $replyReplies = collect(factory(Comment::class, 3)->raw([
            'pc_id' => $replies->first()->id,
        ]))->map(function (array $raw) use ($user):Comment {
            return $user->comments()->create($raw);
        });

        dispatch_now(new UpdateCommentStats($comment));

        $this->assertEquals([
            'reply_count' => 3,
        ], $this->stats($comment->fresh()));

        $this->assertEquals([
            2, 2, 2,
        ], $replyReplies->pluck('depth')->all());
    }

}
