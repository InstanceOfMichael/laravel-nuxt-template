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
 * @group update
 * @group comments
 */
class UpdateTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Comment */
    protected $comment;
    /** @var \App\Side */
    protected $question;
    /** @var \App\Claimside */
    protected $claimside;
    /** @var \App\Comment */
    protected $updatedComment;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 4)->create();
        $this->question = factory(Side::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->question->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->claim->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->question->comments[0]->setRelation('op', $this->users[0]);
        $this->claim->comments[0]->setRelation('op', $this->users[0]);

        $this->claimside = factory(Claimside::class)->create([
            'op_id' => $this->users[0]->id,
            'claim_id' => $this->claim->id,
            'side_id' => $this->question->id,
        ]);
        $this->claimside->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->claimside->comments[0]->setRelation('op', $this->users[0]);

        $this->updatedComment = factory(Comment::class)->make();
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedComment->title,
            'text'  => $this->updatedComment->text,
        ];
    }

    public function testUpdateClaimsideCommentAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->claimside->comments[0]->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'text'  => $this->updatedComment->text,
                'op_id' => $this->claimside->comments[0]->op->id,
                'op' => [
                    'id'     => $this->claimside->comments[0]->op->id,
                    'handle' => $this->claimside->comments[0]->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateClaimsideCommentAsUserWhoIsNotOp()
    {
        $this->actingAs($this->users[3])
            ->patchJson('/comments/'.$this->claimside->comments[0]->id, $this->getPayload())
            ->assertStatus(403);
    }

    public function testUpdateSideCommentAsUserWithCommentableEndpoint()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/claimsides/'.$this->claimside->id.'/comments/'.$this->question->comments[0]->id, $this->getPayload())
            ->assertStatus(405);
    }
}
