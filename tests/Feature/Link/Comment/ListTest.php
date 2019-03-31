<?php

namespace Tests\Feature\Link\Comment;

use App\User;
use App\Comment;
use App\Link;
use App\Claim;
use Tests\TestCase;

/**
 * @group list
 * @group comments
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\CommentTopic[] */
    protected $commentables;
    /** @var \App\Link */
    protected $link;
    /** @var \App\Claim */
    protected $claim;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();

        $this->commentables = collect([
            $this->link = factory(Link::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
            $this->claim = factory(Claim::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
            factory(Link::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
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

        $this->comments = Comment::query()
            ->take(9)
            ->with('op')
            ->orderBy('comments.id', 'desc')
            ->get();

        $this->assertEquals(9, Comment::query()->count());
    }

    public function testListLinkCommentsAsUser()
    {
        $comments = $this->comments
            ->where('context_type', $this->link->getMorphClass())
            ->where('context_id', $this->link->id)
            ->sortByDesc('id')
            ->values();
        $this->actingAs($this->users[0])
            ->getJson('/links/'.$this->link->id.'/comments')
            ->assertStatus(200)
            ->assertJson([
                'data' => $comments->map(function (Comment $c):array {
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
                'total' => $comments->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testListLinkCommentsAsGuest()
    {
        $comments = $this->comments
            ->where('context_type', $this->link->getMorphClass())
            ->where('context_id', $this->link->id)
            ->sortByDesc('id')
            ->values();
        $this->getJson('/links/'.$this->link->id.'/comments')
            ->assertStatus(200)
            ->assertJson([
                'data' => $comments->map(function (Comment $c):array {
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
                'total' => $comments->count(),
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
