<?php

namespace Tests\Feature\Claimrelation\Comment;

use App\Claimrelation;
use App\User;
use App\Claim;
use App\Comment;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UpdateTest extends TestCase
{
    /** @var \App\User[] */
    protected $users;
    /** @var \App\Comment */
    protected $comment;
    /** @var \App\Claim */
    protected $claim;
    /** @var \App\Claimrelation */
    protected $claimrelation;
    /** @var \App\Comment */
    protected $updatedComment;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 4)->create();
        $this->question = factory(Claim::class)->create([
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

        $this->claimrelation = factory(Claimrelation::class)->create([
            'op_id' => $this->users[0]->id,
            'rc_id' => $this->claim->id,
            'pc_id' => $this->question->id,
        ]);
        $this->claimrelation->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->claimrelation->comments[0]->setRelation('op', $this->users[0]);

        $this->updatedComment = factory(Comment::class)->make();
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedComment->title,
            'text'  => $this->updatedComment->text,
        ];
    }

    public function testUpdateClaimrelationCommentAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->claimrelation->comments[0]->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'text'  => $this->updatedComment->text,
                'op_id' => $this->claimrelation->comments[0]->op->id,
                'op' => [
                    'id'     => $this->claimrelation->comments[0]->op->id,
                    'handle' => $this->claimrelation->comments[0]->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateClaimrelationCommentAsUserWhoIsNotOp()
    {
        $this->actingAs($this->users[3])
            ->patchJson('/comments/'.$this->claimrelation->comments[0]->id, $this->getPayload())
            ->assertStatus(403);
    }

    public function testUpdateClaimCommentAsUserWithCommentableEndpoint()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/claimrelations/'.$this->claimrelation->id.'/comments/'.$this->question->comments[0]->id, $this->getPayload())
            ->assertStatus(405);
    }
}
