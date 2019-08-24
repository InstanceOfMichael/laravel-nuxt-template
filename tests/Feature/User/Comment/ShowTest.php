<?php

namespace Tests\Feature\User\Comment;

use App\User;
use App\Comment;
use Tests\TestCase;

/**
 * @group show
 * @group comments
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Comment[] */
    protected $comments;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 4)->create();
        $this->comments = collect([
            factory(Comment::class)->create([
                'op_id' => $this->users[2]->id,
            ]),
            factory(Comment::class)->create([
                'op_id' => $this->users[3]->id,
            ]),
        ]);
    }

    public function testShowCommentAsUser()
    {
        foreach ($this->comments as $comment) {
            $this->actingAs($this->users[0])
                ->getJson('/users/'.$comment->op->id.'/comments/'.$comment->id)
                ->assertStatus(404);
        }
    }

    public function testShowCommentAsGuest()
    {
        foreach ($this->comments as $comment) {
            $this->getJson('/users/'.$comment->op->id.'/comments/'.$comment->id)
                ->assertStatus(404);
        }
    }
}
