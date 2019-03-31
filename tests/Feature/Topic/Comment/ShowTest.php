<?php

namespace Tests\Feature\Topic\Comment;

use App\User;
use App\Claim;
use App\Comment;
use App\Topic;
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
    /** @var \App\Topic */
    protected $topic;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 4)->create();
        $this->topic = factory(Topic::class)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->comments = collect([
            $this->topic->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[2]->id,
            ])),
            $this->claim->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[3]->id,
            ])),
        ]);
    }

    public function testShowTopicCommentAsUser()
    {
        foreach ($this->comments as $comment) {
            $this->actingAs($this->users[0])
                ->getJson('/topics/'.$this->topic->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }

    public function testShowTopicCommentAsGuest()
    {
        foreach ($this->comments as $comment) {
            $this->getJson('/topics/'.$this->topic->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }
}
