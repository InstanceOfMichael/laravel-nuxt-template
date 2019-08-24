<?php

namespace Tests\Feature\Comment;

use App\User;
use App\Comment;
use Tests\TestCase;

/**
 * @group list
 */
class ListTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Comment[] */
    protected $parentcomments;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 9)->create();

        $this->parentcomments = collect([
            factory(Comment::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
            factory(Comment::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
            factory(Comment::class)->create([ 'op_id' => factory(User::class)->create()->id ]),
        ]);

        $this->parentcomments->each(function ($parentcomment) {
            $firstComment = factory(Comment::class)->create([
                'op_id' => factory(User::class)->create()->id,
                'pc_id' => $parentcomment->id,
            ]);
            factory(Comment::class)->create([
                'op_id' => factory(User::class)->create()->id,
                'pc_id' => $parentcomment->id,
            ]);
            factory(Comment::class)->create([
                'op_id' => factory(User::class)->create()->id,
                'pc_id' => $firstComment->id,
            ]);
        });

        $this->comments = Comment::query()
            ->with('op')
            ->orderBy('comments.id', 'desc')
            ->get();

        $this->assertEquals(12, Comment::query()->count());
    }

    public function testListCommentsAsUser()
    {
        $comments = $this->comments;
        $this->actingAs($this->users[0])
            ->getJson('/comments')
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

    public function testListCommentsAsGuest()
    {
        $comments = $this->comments;
        $this->getJson('/comments')
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
