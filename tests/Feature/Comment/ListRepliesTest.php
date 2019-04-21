<?php

namespace Tests\Feature\Comment;

use App\User;
use App\Comment;
use App\Question;
use App\Claim;
use Tests\TestCase;

/**
 * @group list
 */
class ListRepliesTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\CommentTopic[] */
    protected $commentables;
    /** @var \App\Question */
    protected $question;
    /** @var \App\Claim */
    protected $claim;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();

        $this->commentables = collect([
            $this->question = factory(Question::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
            $this->claim = factory(Claim::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
            factory(Question::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
        ]);

        $this->commentables->each(function ($commentable) {
            $commentable->comments()->create(factory(Comment::class)->raw([ 'op_id' => factory(User::class)->create()->id ]));
            $commentable->comments()->create(factory(Comment::class)->raw([ 'op_id' => factory(User::class)->create()->id ]));
        });

        $this->commentables->map(function ($commentable) {
            $commentable->comments()->create(factory(Comment::class)->raw([
                'op_id' => factory(User::class)->create()->id,
                'pc_id' => $commentable->comments()->first()->id,
            ]));
        });

        $commentsWithReplies = Comment::query()->where('reply_count','>',0)->get();

        $this->assertCount(3, $commentsWithReplies);

        $this->parentComment = $commentsWithReplies->first();
        $this->assertEquals(1, $this->parentComment->childComments()->count());
    }

    public function testListReplyCommentsAsUser()
    {
        $this->actingAs($this->users[0])
            ->getJson('/comments?pc_id='.$this->parentComment->id)
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->parentComment->childComments->map(function (Comment $c):array {
                    return [
                        'id'    => $c->id,
                        'text'  => $c->text,
                        'pc_id' => $c->pc_id,
                        'op_id' => $c->op_id,
                        'op' => [
                            'id'     => $c->op->id,
                            'handle' => $c->op->handle,
                        ],
                    ];
                })->all(),
                'total' => 1,
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListReplyCommentsAsGuest()
    {
        $this->getJson('/comments?pc_id='.$this->parentComment->id)
            ->assertStatus(200)
            ->assertJson([
                'data' => $this->parentComment->childComments->map(function (Comment $c):array {
                    return [
                        'id'    => $c->id,
                        'text'  => $c->text,
                        'pc_id' => $c->pc_id,
                        'op_id' => $c->op_id,
                        'op' => [
                            'id'     => $c->op->id,
                            'handle' => $c->op->handle,
                        ],
                    ];
                })->all(),
                'total' => 1,
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
