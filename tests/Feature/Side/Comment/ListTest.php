<?php

namespace Tests\Feature\Side\Comment;

use App\User;
use App\Comment;
use App\Side;
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
    /** @var \App\Side */
    protected $side;
    /** @var \App\Claim */
    protected $claim;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();

        $this->commentables = collect([
            $this->side = factory(Side::class)->create(),
            $this->claim = factory(Claim::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
            factory(Side::class)->create(),
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

    public function testListSideCommentsAsUser()
    {
        $comments = $this->comments
            ->where('context_type', $this->side->getMorphClass())
            ->where('context_id', $this->side->id)
            ->sortByDesc('id')
            ->values();
        $this->actingAs($this->users[0])
            ->getJson('/sides/'.$this->side->id.'/comments')
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

    public function testListSideCommentsAsGuest()
    {
        $comments = $this->comments
            ->where('context_type', $this->side->getMorphClass())
            ->where('context_id', $this->side->id)
            ->sortByDesc('id')
            ->values();
        $this->getJson('/sides/'.$this->side->id.'/comments')
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
