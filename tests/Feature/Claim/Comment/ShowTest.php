<?php

namespace Tests\Feature\Claim\Comment;

use App\User;
use App\Claim;
use App\Comment;
use App\Question;
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
                ->getJson('/questions/'.$this->question->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }

    public function testShowClaimCommentAsGuest()
    {
        foreach ($this->comments as $comment) {
            $this->getJson('/questions/'.$this->question->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }
}
