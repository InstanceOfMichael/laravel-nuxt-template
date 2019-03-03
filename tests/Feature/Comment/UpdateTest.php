<?php

namespace Tests\Feature\Comment;

use App\User;
use App\Comment;
use App\Claim;
use App\Question;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

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

    public function testUpdateQuestionCommentAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->question->comments[0]->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'text'  => $this->updatedComment->text,
                'op_id' => $this->question->comments[0]->op->id,
                'op' => [
                    'id'     => $this->question->comments[0]->op->id,
                    'handle' => $this->question->comments[0]->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
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
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateQuestionCommentAsUserWhoIsNotOp()
    {
        $this->actingAs($this->users[3])
            ->patchJson('/comments/'.$this->question->comments[0]->id, $this->getPayload())
            ->assertStatus(403);
    }

    public function testUpdateClaimCommentAsUserWhoIsNotOp()
    {
        $this->actingAs($this->users[3])
            ->patchJson('/comments/'.$this->claim->comments[0]->id, $this->getPayload())
            ->assertStatus(403);
    }

    public function testUpdateCommentPatchWithoutId()
    {
        $this->patchJson('/comments', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateClaimCommentAsGuest()
    {
        $this->patchJson('/comments/'.$this->claim->comments[0]->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateQuestionCommentAsGuest()
    {
        $this->patchJson('/comments/'.$this->question->comments[0]->id, $this->getPayload())
            ->assertStatus(401);
    }

    public function testUpdateCommentEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->claim->comments[0]->id, [])
            ->assertStatus(200)
            ->assertJson([
                'text'  => $this->claim->comments[0]->text,
                'op_id' => $this->claim->comments[0]->op->id,
                'op' => [
                    'id'     => $this->claim->comments[0]->op->id,
                    'handle' => $this->claim->comments[0]->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateCommentEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->question->comments[0]->id, [
                'text' => null,
            ])
            ->assertStatus(422)
            ->assertExactJson([
                "errors" => [
                    "text" => ["The text must be a string."],
                ],
                "message" => "The given data was invalid.",
            ])
            ->assertDontExposeUserEmails($this->users);
    }
}
