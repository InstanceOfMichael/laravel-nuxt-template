<?php

namespace Tests\Feature\User\Comment;

use App\User;
use App\Comment;
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
        $this->comments = collect([
            factory(Comment::class)->create([
                'op_id' => $this->users[0]->id,
            ]),
            factory(Comment::class)->create([
                'op_id' => $this->users[0]->id,
            ]),
        ]);
        $this->comments[0]->setRelation('op', $this->users[0]);
        $this->comments[0]->setRelation('op', $this->users[0]);
        $this->updatedComment = factory(Comment::class)->make();
    }

    protected function getPayload(): array {
        return [
            'title' => $this->updatedComment->title,
            'text'  => $this->updatedComment->text,
        ];
    }

    public function testUpdateCommentAsUserWithCommentableEndpoint()
    {
        $this->actingAs($this->users[0])
            ->patchJson('/users/'.$this->users[0]->id.'/comments/'.$this->comments[0]->id, $this->getPayload())
            ->assertStatus(404);
    }
}
