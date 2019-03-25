<?php

namespace Tests\Feature\Claimrelation\Comment;

use App\Claimrelation;
use App\User;
use App\Claim;
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
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Claimrelation */
    protected $claimrelation;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 7)->create();
        $this->question = factory(Claim::class)->create([
            'op_id' => $this->users[0]->id,
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claimrelation = factory(Claimrelation::class)->create([
            'op_id' => $this->users[3]->id,
            'rc_id' => $this->claim->id,
            'pc_id' => $this->question->id,
        ]);
        $this->comments = collect([
            $this->question->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[4]->id,
            ])),
            $this->claim->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[5]->id,
            ])),
            $this->claimrelation->comments()->create(factory(Comment::class)->raw([
                'op_id' => $this->users[6]->id,
            ])),
        ]);
    }

    public function testShowClaimCommentAsUser()
    {
        foreach ($this->comments as $comment) {
            $this->actingAs($this->users[0])
                ->getJson('/claimrelations/'.$this->claimrelation->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }

    public function testShowClaimCommentAsGuest()
    {
        foreach ($this->comments as $comment) {
            $this->getJson('/claimrelations/'.$this->claimrelation->id.'/comments/'.$comment->id)
                ->assertStatus(405);
        }
    }
}
