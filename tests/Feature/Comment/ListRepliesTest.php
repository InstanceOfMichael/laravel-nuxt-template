<?php

namespace Tests\Feature\Comment;

use App\User;
use App\Comment;
use Tests\TestCase;

/**
 * @group list
 */
class ListRepliesTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Comments[] */
    protected $parentcomments;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();

        $this->parentcomments = collect([
            $this->users[0]->comments()->create(factory(Comment::class)->raw([ 'op_id' => factory(User::class)->create()->id ])),
            $this->users[0]->comments()->create(factory(Comment::class)->raw([ 'op_id' => factory(User::class)->create()->id ])),
            $this->users[0]->comments()->create(factory(Comment::class)->raw([ 'op_id' => factory(User::class)->create()->id ])),
        ]);
        $this->parentcomments->each(function ($parentcomment) {
            Comment::create(factory(Comment::class)->raw([
                'op_id' => factory(User::class)->create()->id,
                'pc_id' => $parentcomment->id,
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
