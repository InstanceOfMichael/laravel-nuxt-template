<?php

namespace Tests\Feature\Comment;

use App\User;
use App\Comment;
use Tests\TestCase;

/**
 * @group update
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
        $this->users[0]->comments()->create(factory(Comment::class)->raw());
        $this->users[0]->comments()->create(factory(Comment::class)->raw());
        $this->users[0]->comments[0]->setRelation('op', $this->users[0]);
        $this->users[0]->comments[1]->setRelation('op', $this->users[0]);

        $this->updatedComment = factory(Comment::class)->make();
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedComment->title,
            'text'  => $this->updatedComment->text,
        ];
    }

    public function testUpdateCommentAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->users[0]->comments[0]->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'text'  => $this->updatedComment->text,
                'op_id' => $this->users[0]->comments[0]->op->id,
                'op' => [
                    'id'     => $this->users[0]->comments[0]->op->id,
                    'handle' => $this->users[0]->comments[0]->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateCommentAsUserWhoIsNotOp()
    {
        $this->actingAs($this->users[3])
            ->patchJson('/comments/'.$this->users[0]->comments[0]->id, $this->getPayload())
            ->assertStatus(403);
    }

    public function testUpdateCommentPatchWithoutId()
    {
        $this->patchJson('/comments', $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateCommentAsGuest()
    {
        $this->patchJson('/comments/'.$this->users[0]->comments[0]->id, $this->getPayload())
            ->assertStatus(401);
    }


    public function testUpdateCommentEmptyPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->users[0]->comments[0]->id, [])
            ->assertStatus(200)
            ->assertJson([
                'text'  => $this->users[0]->comments[0]->text,
                'op_id' => $this->users[0]->comments[0]->op->id,
                'op' => [
                    'id'     => $this->users[0]->comments[0]->op->id,
                    'handle' => $this->users[0]->comments[0]->op->handle,
                ],
            ])
            ->assertDontExposeUserEmails($this->users);
    }

    public function testUpdateCommentEmptyNullPayload()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->users[0]->comments[0]->id, [
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
