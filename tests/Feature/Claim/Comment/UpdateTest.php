<?php

namespace Tests\Feature\Claim\Comment;

use App\User;
use App\Comment;
use App\Claim;
use App\Question;
use Tests\TestCase;

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
        $this->question = factory(Question::class)->create([
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
        $this->updatedComment = factory(Comment::class)->make();
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedComment->title,
            'text'  => $this->updatedComment->text,
        ];
    }

    public function testUpdateClaimCommentAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->claim->comments[0]->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'text'  => $this->updatedComment->text,
                'op_id' => $this->claim->comments[0]->op->id,
                'op' => [
                    'id'     => $this->claim->comments[0]->op->id,
                    'handle' => $this->claim->comments[0]->op->handle,
                ],
            ])
            ->assertDontSeeText($this->users[0]->email)
            ->assertJsonMissing(['email']);
    }

    public function testUpdateClaimCommentAsUserWhoIsNotOp()
    {
        $this->actingAs($this->users[3])
            ->patchJson('/comments/'.$this->claim->comments[0]->id, $this->getPayload())
            ->assertStatus(403);
    }

    public function testUpdateQuestionCommentAsUserWithCommentableEndpoint()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/claims/'.$this->claim->id.'/comments/'.$this->claim->comments[0]->id, $this->getPayload())
            ->assertStatus(405);
    }
}