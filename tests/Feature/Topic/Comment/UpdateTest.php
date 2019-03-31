<?php

namespace Tests\Feature\Topic\Comment;

use App\User;
use App\Comment;
use App\Claim;
use App\Topic;
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
        $this->topic = factory(Topic::class)->create();
        $this->claim = factory(Claim::class)->create([
            'op_id' => $this->users[2]->id,
        ]);
        $this->topic->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->claim->comments()->create(factory(Comment::class)->raw([
            'op_id' => $this->users[0]->id,
        ]));
        $this->topic->comments[0]->setRelation('op', $this->users[0]);
        $this->claim->comments[0]->setRelation('op', $this->users[0]);
        $this->updatedComment = factory(Comment::class)->make();
    }

    protected function getPayload(): array {
        return [
            'name' => $this->updatedComment->name,
            'text'  => $this->updatedComment->text,
        ];
    }

    public function testUpdateTopicCommentAsUser()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/comments/'.$this->topic->comments[0]->id, $this->getPayload())
            ->assertStatus(200)
            ->assertJson([
                'text'  => $this->updatedComment->text,
                'op_id' => $this->topic->comments[0]->op->id,
                'op' => [
                    'id'     => $this->topic->comments[0]->op->id,
                    'handle' => $this->topic->comments[0]->op->handle,
                ],
            ])
            ->assertDontSeeText($this->users[0]->email)
            ->assertJsonMissing(['email']);
    }

    public function testUpdateTopicCommentAsUserWhoIsNotOp()
    {
        $this->actingAs($this->users[3])
            ->patchJson('/topics/'.$this->topic->id.'/comments/'.$this->topic->comments[0]->id, $this->getPayload())
            ->assertStatus(403);
    }

    public function testUpdateTopicCommentAsUserWithCommentableEndpoint()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/topics/'.$this->topic->id.'/comments/'.$this->topic->comments[0]->id, $this->getPayload())
            ->assertStatus(405);
    }

    public function testUpdateTopicCommentAsGuest()
    {
        $this->patchJson('/comments/'.$this->topic->comments[0]->id, $this->getPayload())
            ->assertStatus(401);
    }
}
