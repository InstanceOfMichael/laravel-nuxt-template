<?php

namespace Tests\Feature\Claimside\Comment;

use App\Claimside;
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
    /** @var \App\Claimside */
    protected $claimside;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 7)->create();
        $this->side = factory(Side::class)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claimside = factory(Claimside::class)->create([
            'op_id' => $this->users[3]->id,
            'claim_id' => $this->claim->id,
            'side_id' => $this->side->id,
        ]);
        $this->comments = collect([
            $this->side->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[4]->id,
            ])),
            $this->claim->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[5]->id,
            ])),
            $this->claimside->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[6]->id,
            ])),
        ]);
    }

    public function testShowClaimCommentAsUser()
    {
        foreach ($this->comments as $comment) {
            $this->actingAs($this->users[0])
                ->getJson('/claimsides/'.$this->claimside->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }

    public function testShowClaimCommentAsGuest()
    {
        foreach ($this->comments as $comment) {
            $this->getJson('/claimsides/'.$this->claimside->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }
}
