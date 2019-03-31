<?php

namespace Tests\Feature\Comment;

use App\User;
use App\Claim;
use App\Comment;
use App\Question;
use Tests\TestCase;

/**
 * @group show
 */
class ShowTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Comment[] */
    protected $comments;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Question */
    protected $question;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 4)->create();
        $this->question = factory(Question::class)->create([
            'op_id' => $this->users[0]->id,
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->comments = collect([
            $this->question->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[2]->id,
            ])),
            $this->claim->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[3]->id,
            ])),
        ]);
    }

    public function testShowClaimCommentAsUser()
    {
        foreach ($this->comments as $comment) {
            $this->actingAs($this->users[0])
                ->getJson('/comments/'.$comment->id)
                ->assertStatus(200)
                ->assertJson([
                    'text'  => $comment->text,
                    'pc_id' => $comment->pc_id,
                    'op_id' => $comment->op_id,
                    'op' => [
                        'id'     => $comment->op->id,
                        'handle' => $comment->op->handle,
                    ],
                ])
                ->assertDontExposeUserEmails($this->users);
        }
    }

    public function testShowCommentAsGuest()
    {
        foreach ($this->comments as $comment) {
            $this->getJson('/comments/'.$comment->id)
                ->assertStatus(200)
                ->assertJson([
                    'text'  => $comment->text,
                    'pc_id' => $comment->pc_id,
                    'op_id' => $comment->op_id,
                    'op' => [
                        'id'     => $comment->op->id,
                        'handle' => $comment->op->handle,
                    ],
                ])
                ->assertDontExposeUserEmails($this->users);
        }
    }
}
