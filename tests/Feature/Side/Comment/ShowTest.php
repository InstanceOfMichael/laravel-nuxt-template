<?php

namespace Tests\Feature\Side\Comment;

use App\User;
use App\Claim;
use App\Comment;
use App\Side;
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
    /** @var \App\Side */
    protected $side;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 4)->create();
        $this->side = factory(Side::class)->create([
            'op_id' => $this->users[0]->id,
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->comments = collect([
            $this->side->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[2]->id,
            ])),
            $this->claim->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[3]->id,
            ])),
        ]);
    }

    public function testShowSideCommentAsUser()
    {
        foreach ($this->comments as $comment) {
            $this->actingAs($this->users[0])
                ->getJson('/sides/'.$this->side->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }

    public function testShowSideCommentAsGuest()
    {
        foreach ($this->comments as $comment) {
            $this->getJson('/sides/'.$this->side->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }
}
