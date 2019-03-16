<?php

namespace Tests\Feature\Side\Comment;

use App\User;
use App\Comment;
use App\Claim;
use App\Side;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

/**
 * @group update
 * @group comments
 */
class UpdateTest extends TestCase
{
    /** @var \App\User */
    protected $user;
    /** @var \App\Comment */
    protected $comment;
    /** @var \App\Comment */
    protected $updatedComment;

    public function setUp()
    {
        parent::setUp();

        $this->users = factory(User::class, 4)->create();
        $this->side = factory(Side::class)->create([
            'op_id' => $this->users[1]->id,
        ]);
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->side->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->claim->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->side->comments[0]->setRelation('op', $this->users[0]);
        $this->claim->comments[0]->setRelation('op', $this->users[0]);
        $this->updatedComment = factory(Comment::class)->make();
    }

    protected function getPayload(): array {
        return [
            'name' => $this->updatedComment->name,
            'text'  => $this->updatedComment->text,
        ];
    }

    public function testUpdateSideCommentAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->side->comments[0]->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'text'  => $this->updatedComment->text,
                'op_id' => $this->side->comments[0]->op->id,
                'op' => [
                    'id'     => $this->side->comments[0]->op->id,
                    'handle' => $this->side->comments[0]->op->handle,
                ],
            ])
            ->assertDontSeeText($this->users[0]->email)
            ->assertJsonMissing(['email']);
    }

    public function testUpdateSideCommentAsUserWhoIsNotOp()
    {
        $this->actingAs($this->users[3])
            ->patchJson('/sides/'.$this->side->id.'/comments/'.$this->side->comments[0]->id, $this->getPayload())
            ->assertStatus(403);
    }

    public function testUpdateSideCommentAsUserWithCommentableEndpoint()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/sides/'.$this->side->id.'/comments/'.$this->side->comments[0]->id, $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateSideCommentAsGuest()
    {
        $this->patchJson('/comments/'.$this->side->comments[0]->id, $this->getPayload())
            ->assertStatus(401);
    }
}
