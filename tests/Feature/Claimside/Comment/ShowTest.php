<?php

namespace Tests\Feature\Claimside\Comment;

use App\Claimside;
use App\User;
use App\Claim;
use App\Comment;
use App\Side;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

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
    /** @var \App\Side */
    protected $question;
    /** @var \App\Claimside */
    protected $claimside;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 7)->create();
        $this->question = factory(Side::class)->create([
            'op_id' => $this->users[0]->id,
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claimside = factory(Claimside::class)->create([
            'op_id' => $this->users[3]->id,
            'claim_id' => $this->claim->id,
            'side_id' => $this->question->id,
        ]);
        $this->comments = collect([
            $this->question->comments()->create(factory(Comment::class)->raw([
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

    /**
     * @group comment
     */
    public function testShowClaimCommentAsUser()
    {
        foreach ($this->comments as $comment) {
            $this->actingAs($this->users[0])
                ->getJson('/claimsides/'.$this->claimside->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }

    /**
     * @group comment
     */
    public function testShowClaimCommentAsGuest()
    {
        foreach ($this->comments as $comment) {
            $this->getJson('/claimsides/'.$this->claimside->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }
}
